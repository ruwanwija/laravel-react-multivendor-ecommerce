import React from 'react'
import { Product } from '@/types'
import { Link } from '@inertiajs/react'
import CurrencyFormatter from '../core/CurrencyFormatter'

interface ProductItemProps {
  product: Product
}

const ProductItem: React.FC<ProductItemProps> = ({ product }) => {
    return (
    <div className="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow">
             <Link href={route('product.show', { product: product.slug })}>
        <figure>
          <img 
              src={product.image || '/placeholder.jpg'} 
              alt={product.title}
              className='aspect-square object-cover' 
          />
        </figure>
      </Link>
      <div className="card-body">
          <h2 className="card-title">{product.title}</h2>
          <p>
            by <Link href="/" className='hover:underline'>{product.user.name}</Link>
            in <Link href="/" className='hover:underline'>{product.department.name}</Link>
          </p>
          <div className="card-actions justify-between">
            <button className='btn btn-primary'>Add to Cart</button>
            <span className='text-2xl'>
                <CurrencyFormatter amount={product.price} />
            </span>
          </div>
      </div>
    </div>
  )
}

export default ProductItem
