# Atun Laundry Management System

A comprehensive laundry management system built with Laravel 12, featuring both customer and admin interfaces with a beautiful pastel purple theme.

## Features

### Customer Features
- **User Registration & Login**: Simple registration with email and password
- **Order Creation**: Create laundry orders with service selection and pickup method
- **Order Tracking**: Track order status with unique order codes (ATN-YYYYMMDD-XXX format)
- **Payment Proof Upload**: Upload payment receipts for order verification
- **Order History**: View all past and current orders
- **Public Order Check**: Check order status without logging in

### Admin Features
- **Admin Dashboard**: Overview of orders, revenue, and statistics
- **Order Management**: View, update, and manage all orders (both login and manual)
- **Manual Order Creation**: Create orders for walk-in customers
- **Payment Verification**: Verify customer payment proofs
- **Order Status Updates**: Update order status, weight, and pricing
- **Order Filtering**: Filter orders by status, type, and search terms

### System Features
- **Order Code Generation**: Automatic unique order code generation
- **Order Status Flow**: Complete order lifecycle management
- **Responsive Design**: Mobile-friendly interface with Bootstrap 5
- **Pastel Purple Theme**: Beautiful and modern UI design
- **Role-based Access**: Separate interfaces for customers and admins

## Order Status Flow

1. **Waiting for Pickup** - New order created
2. **Picked & Weighed** - Admin picks up and weighs laundry
3. **Waiting for Payment** - System waits for payment proof
4. **Waiting for Admin Verification** - Admin verifies payment
5. **Processed** - Laundry is being washed/ironed
6. **Completed** - Order ready for pickup/delivery

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd atunlaundry
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Storage setup**
   ```bash
   php artisan storage:link
   ```

6. **Start the application**
   ```bash
   php artisan serve
   ```

## Default Admin Account

- **Email**: admin@atunlaundry.com
- **Password**: admin123

## Default Services

The system comes with pre-configured services:
- Regular Laundry (Rp 8,000/kg, 2 days)
- Express Laundry (Rp 12,000/kg, 1 day)
- Ironing Only (Rp 5,000/kg, 1 day)
- Dry Clean (Rp 15,000/kg, 3 days)
- Wash & Iron (Rp 10,000/kg, 2 days)

## Technology Stack

- **Backend**: Laravel 12
- **Frontend**: Blade Templates + Bootstrap 5
- **Database**: MySQL/SQLite
- **Styling**: Custom CSS with pastel purple theme
- **Icons**: Font Awesome 6

## File Structure

```
app/
├── Http/Controllers/
│   ├── AuthController.php      # Authentication logic
│   ├── HomeController.php      # Home page controller
│   ├── OrderController.php     # Order management
│   └── AdminController.php     # Admin panel logic
├── Models/
│   ├── User.php               # User model with roles
│   ├── Order.php              # Order model with code generation
│   ├── Service.php            # Service model
│   ├── Promotion.php          # Promotion model
│   └── Expense.php            # Expense model
database/
├── migrations/                # Database migrations
└── seeders/                   # Database seeders
resources/views/
├── layouts/
│   └── app.blade.php         # Main layout template
├── auth/                     # Authentication views
├── orders/                   # Order management views
└── admin/                    # Admin panel views
```

## Usage

### For Customers
1. Register an account or login
2. Create a new order by selecting service and providing details
3. Receive order code for tracking
4. Upload payment proof when prompted
5. Track order status and view order history

### For Admins
1. Login with admin credentials
2. Access admin dashboard for overview
3. Manage orders: update status, weight, and pricing
4. Verify payment proofs
5. Create manual orders for walk-in customers

## Order Code Format

Order codes follow the format: `ATN-YYYYMMDD-XXX`
- ATN: System prefix
- YYYYMMDD: Date in YYYY-MM-DD format
- XXX: Random 3-character string

Example: `ATN-20251018-ABC`

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions, please contact the development team or create an issue in the repository.