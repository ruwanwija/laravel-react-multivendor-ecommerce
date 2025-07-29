<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;

class ProductImages extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected static ? string $title = 'Images';

    protected static ? string $navigationIcon = 'heroicon-c-photo';

    public function form (Form $form):Form
    {
        return $form
            ->schema([
              SpatieMediaLibraryFileUpload::make('images')
                ->label(false)
                ->image()
                ->collection('images')
                ->multiple()
                ->openable()
                ->panelLayout('grid')
                ->appendFiles()
                ->preserveFilenames()
                ->columnSpan(2)
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}