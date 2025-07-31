<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enums\ProductVariationTypeEnum;
use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;

class ProductVariations extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected static ? string $title = 'Variations';

    protected static ? string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public function form(Form $form) : Form {
        
        $types = $this->record->variationTypes;
        $fields = [];
        foreach ($types as $type)
        {
            $fields[] = TextInput::make('variation_type_'.($type->id).'.id')
                ->hidden();
            $fields[] = TextInput::make('variation_type_'.($type->id).'.name')
                ->label($type->name);

        }
        return $form
            ->schema([
                Repeater::make('variations')
                    ->label(false)
                    ->collapsible()
                    ->addable(false)
                    ->defaultItems(1)
                    ->schema([
                        Section::make()
                            ->schema($fields)
                            ->columns(3),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric(),
                        TextInput::make('price')
                            ->label('price')
                            ->numeric(),
                    ])
                    ->columns(2)
                    ->columnspan(2)
            ]); 
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];   
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $variations = $this->record->variations->toArray();
        $data['variations'] = $this->mergeCartesianWithExisting($this->record->variationTypes, $variations);
        
        return $data;
    }

    private function generateAllCombinations($variationTypes): array
    {
        $result = [[]];

        foreach ($variationTypes as $index => $variationType)
        {
            $temp = [];

            foreach ($variationType->options as $option)
            {
                // Add the current option to all existing combinations
                foreach ($result as $combination)
                {
                    $newCombination = $combination + [
                        'variation_type_'.($index + 1) => [
                            'id' => $option->id,
                            'name' => $option->name,
                            'label' => $variationType->name,
                        ],
                    ];

                    $temp[] = $newCombination;
                }
            }

            $result = $temp;
        }

        return $result;
    }

    protected function mergeCartesianWithExisting($variationTypes, $existingData): array
    {
        $defaultQuantity = $this->record->quantity;
        $defaultPrice = $this->record->price;
        $cartesianProduct = $this->cartesianProduct($variationTypes, $defaultQuantity, $defaultPrice);
        $mergedResult = [];

        foreach ($cartesianProduct as $product)
        {
            // Extraction Option Ids from the current product combination
            $optionIds = collect($product)
                ->filter(function($value, $key) { return str_starts_with($key, 'variation_type_'); })
                ->map(fn($option) => $option['id'])
                ->values()
                ->toArray();


            //Find matching entry in exsisting date
            $match = array_filter($existingData, function($existingOption) use ($optionIds) {
                return $existingOption['variation_type_option_ids'] === $optionIds;
            });

            //If match is found,override quantity and price
            if(!empty($match)){
                $exsistingEntry = reset($match);
                $product['id']=$exsistingEntry['id'];
                $product['quantity'] = $exsistingEntry['quantity'];
                $product['price'] = $exsistingEntry['price'];
            }else{
                $product['quantity'] = $defaultQuantity;
                $product['price'] = $defaultPrice;
            }

            $mergedResult[] = $product;
        }

        return $mergedResult;
    }

    private function cartesianProduct($variationTypes, $defaultQuantity = null, $defaultPrice = null):array
    {
        $result = [[]];

        foreach ($variationTypes as $index => $variationType)
        {
            $temp = [];

            foreach ($variationType->options as $option)
            {
                //Add the current option to all existing combination 
                foreach ($result as $combination)
                {
                    $newCombination = $combination + [
                        'variation_type_'.($index + 1) => [
                            'id' => $option->id,
                            'name' => $option->name,
                            'label' => $variationType->name,
                        ],
                    ];

                    $temp[] = $newCombination;
                }
            }

            $result = $temp;
        }

        //Add quantity and price to completed combination
        foreach ($result as $combination)
        {
            if(count($combination) === count($variationTypes))
            {
                $combination['quantity'] = $defaultQuantity;
                $combination['price'] = $defaultPrice;
            }
        }
        return $result;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        //Initialize an array to hold the formatted data
        $formattedData = [];

        //Loop through each variation to restructure it
        foreach ($data['variations'] as $option)
        {
            $variationTypeOptionIds = [];

            foreach ($this -> record->variationTypes as $i=>$variationType)
            {
                $variationTypeOptionIds[] = $option['variation_type_'.($variationType->id)];
            }

            $quantity = $option['quantity'];
            $price = $option['price'];

            //Prepare the data structure for the data
            $formattedData[] = [
                'variation_type_option_ids' => $variationTypeOptionIds,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }
        $data['variations'] = $formattedData;
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $variations = $data['variations'];
        unset($data['variations']);

        $variations = collect($variations)->map(function($variation) {
            $data = [
                'variation_type_option_ids' => json_encode($variation['variation_type_option_ids']),
                'quantity' => $variation['quantity'],
                'price' => $variation['price'],
            ];
            
            // Only include id if it exists (for existing records)
            if (isset($variation['id'])) {
                $data['id'] = $variation['id'];
            }
            
            return $data;
        })->toArray();
            

        $record->variations()->delete();
        $record->variations()->createMany($variations);

        return $record;
    }
}

?>