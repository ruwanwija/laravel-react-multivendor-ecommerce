import React from 'react'

const CurrencyFormatter = ({
  amount,
  currency = "LKR",
  locale = "en-US"
}: {
  amount: number
  currency?: string,
  locale?: string
}) => {
  return new Intl.NumberFormat(locale, {
    style: 'currency',
    currency
  }).format(amount)
}

export default CurrencyFormatter
