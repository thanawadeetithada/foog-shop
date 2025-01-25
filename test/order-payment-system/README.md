# Order Payment System

This project is an order payment system that allows users to browse products from various stores, add them to a shopping cart, and proceed to checkout for payment. 

## Project Structure

```
order-payment-system
├── src
│   ├── db_connection.php          # Establishes a connection to the database
│   ├── order_summary.php           # Displays the order summary and handles order confirmation
│   ├── cart.php                    # Manages shopping cart functionality
│   ├── store_products.php          # Retrieves and displays products from a specific store
│   ├── add_to_cart.php             # Handles adding products to the shopping cart
│   ├── remove_from_cart.php        # Manages removal of items from the shopping cart
│   ├── checkout.php                # Checkout page for reviewing orders
│   ├── payment_confirmation.php     # Processes payment confirmation
│   └── css
│       ├── order_summary.css       # Styles for the order summary page
│       ├── manage_stores.css       # Styles for the cart management page
│       └── store.css               # Styles for the store products page
├── sql
│   └── create_database.sql         # SQL commands to create the database and tables
├── .gitignore                      # Specifies files to ignore in version control
└── README.md                       # Documentation for the project
```

## Setup Instructions

1. **Clone the repository**:
   ```
   git clone <repository-url>
   cd order-payment-system
   ```

2. **Set up the database**:
   - Open the `sql/create_database.sql` file and execute the SQL commands in your database management tool to create the necessary database and tables.

3. **Configure database connection**:
   - Update the `src/db_connection.php` file with your database credentials.

4. **Run the application**:
   - You can run the application on a local server (e.g., XAMPP, WAMP) or deploy it on a web server that supports PHP.

## Usage

- Navigate to the store products page to browse available products.
- Add items to your cart and manage them through the cart page.
- Proceed to checkout to review your order and confirm payment.

## Contributing

Feel free to submit issues or pull requests for improvements and bug fixes. 

## License

This project is open-source and available under the MIT License.