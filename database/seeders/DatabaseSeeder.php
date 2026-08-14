<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\RestaurantTable;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@restaurant.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        // Extra staff
        User::firstOrCreate(['email' => 'cashier@restaurant.com'], [
            'name' => 'John Cashier', 'password' => Hash::make('password'),
        ]);

        // Categories
        $categories = [
            ['name' => 'Starters',    'icon' => 'bi-egg-fried',      'color' => '#FF6B35'],
            ['name' => 'Main Course', 'icon' => 'bi-journal-text',    'color' => '#3b82f6'],
            ['name' => 'Pizza',       'icon' => 'bi-circle',          'color' => '#e91e63'],
            ['name' => 'Burgers',     'icon' => 'bi-cup-hot',         'color' => '#8B4513'],
            ['name' => 'Pasta',       'icon' => 'bi-columns-gap',     'color' => '#FF9800'],
            ['name' => 'Desserts',    'icon' => 'bi-cake2',           'color' => '#9c27b0'],
            ['name' => 'Drinks',      'icon' => 'bi-cup-straw',       'color' => '#00BCD4'],
            ['name' => 'Salads',      'icon' => 'bi-leaf',            'color' => '#4caf50'],
        ];

        $catModels = [];
        foreach ($categories as $i => $cat) {
            $catModels[] = Category::firstOrCreate(['name' => $cat['name']], array_merge($cat, ['sort_order' => $i]));
        }

        // Menu items
        $menuItems = [
            // Starters (0)
            ['name' => 'Garlic Bread',        'price' => 4.99,  'cost_price' => 1.20, 'prep' => '8 min',  'cat' => 0, 'featured' => false],
            ['name' => 'Mozzarella Sticks',   'price' => 7.99,  'cost_price' => 2.50, 'prep' => '10 min', 'cat' => 0, 'featured' => false],
            ['name' => 'Chicken Wings',        'price' => 10.99, 'cost_price' => 4.00, 'prep' => '15 min', 'cat' => 0, 'featured' => true],
            ['name' => 'Soup of the Day',      'price' => 5.99,  'cost_price' => 1.80, 'prep' => '5 min',  'cat' => 0, 'featured' => false],
            // Main Course (1)
            ['name' => 'Grilled Salmon',       'price' => 18.99, 'cost_price' => 8.00, 'prep' => '20 min', 'cat' => 1, 'featured' => true],
            ['name' => 'BBQ Ribs',             'price' => 22.99, 'cost_price' => 9.50, 'prep' => '25 min', 'cat' => 1, 'featured' => true],
            ['name' => 'Chicken Steak',        'price' => 15.99, 'cost_price' => 6.00, 'prep' => '18 min', 'cat' => 1, 'featured' => false],
            ['name' => 'Vegetable Stir Fry',   'price' => 12.99, 'cost_price' => 4.00, 'prep' => '15 min', 'cat' => 1, 'featured' => false],
            // Pizza (2)
            ['name' => 'Margherita Pizza',     'price' => 12.99, 'cost_price' => 4.50, 'prep' => '20 min', 'cat' => 2, 'featured' => true],
            ['name' => 'Pepperoni Pizza',      'price' => 14.99, 'cost_price' => 5.50, 'prep' => '20 min', 'cat' => 2, 'featured' => true],
            ['name' => 'BBQ Chicken Pizza',    'price' => 15.99, 'cost_price' => 6.00, 'prep' => '22 min', 'cat' => 2, 'featured' => false],
            ['name' => 'Veggie Pizza',         'price' => 13.99, 'cost_price' => 4.80, 'prep' => '20 min', 'cat' => 2, 'featured' => false],
            // Burgers (3)
            ['name' => 'Classic Burger',       'price' => 9.99,  'cost_price' => 3.50, 'prep' => '12 min', 'cat' => 3, 'featured' => true],
            ['name' => 'Double Cheese Burger', 'price' => 12.99, 'cost_price' => 4.50, 'prep' => '14 min', 'cat' => 3, 'featured' => false],
            ['name' => 'Veggie Burger',        'price' => 10.99, 'cost_price' => 3.80, 'prep' => '12 min', 'cat' => 3, 'featured' => false],
            // Pasta (4)
            ['name' => 'Spaghetti Bolognese',  'price' => 13.99, 'cost_price' => 4.50, 'prep' => '18 min', 'cat' => 4, 'featured' => true],
            ['name' => 'Fettuccine Alfredo',   'price' => 12.99, 'cost_price' => 4.00, 'prep' => '15 min', 'cat' => 4, 'featured' => false],
            ['name' => 'Penne Arrabbiata',     'price' => 11.99, 'cost_price' => 3.80, 'prep' => '15 min', 'cat' => 4, 'featured' => false],
            // Desserts (5)
            ['name' => 'Chocolate Lava Cake',  'price' => 7.99,  'cost_price' => 2.50, 'prep' => '10 min', 'cat' => 5, 'featured' => true],
            ['name' => 'Cheesecake',           'price' => 6.99,  'cost_price' => 2.20, 'prep' => '5 min',  'cat' => 5, 'featured' => false],
            ['name' => 'Ice Cream Sundae',     'price' => 5.99,  'cost_price' => 1.80, 'prep' => '5 min',  'cat' => 5, 'featured' => false],
            // Drinks (6)
            ['name' => 'Fresh Orange Juice',   'price' => 3.99,  'cost_price' => 1.00, 'prep' => '3 min',  'cat' => 6, 'featured' => false],
            ['name' => 'Lemonade',             'price' => 3.49,  'cost_price' => 0.80, 'prep' => '3 min',  'cat' => 6, 'featured' => false],
            ['name' => 'Iced Coffee',          'price' => 4.49,  'cost_price' => 1.20, 'prep' => '5 min',  'cat' => 6, 'featured' => true],
            ['name' => 'Still Water',          'price' => 1.99,  'cost_price' => 0.30, 'prep' => '1 min',  'cat' => 6, 'featured' => false],
            ['name' => 'Soft Drink',           'price' => 2.49,  'cost_price' => 0.50, 'prep' => '1 min',  'cat' => 6, 'featured' => false],
            // Salads (7)
            ['name' => 'Caesar Salad',         'price' => 9.99,  'cost_price' => 3.00, 'prep' => '8 min',  'cat' => 7, 'featured' => true],
            ['name' => 'Greek Salad',          'price' => 8.99,  'cost_price' => 2.80, 'prep' => '8 min',  'cat' => 7, 'featured' => false],
        ];

        $menuModels = [];
        foreach ($menuItems as $item) {
            $menuModels[] = MenuItem::firstOrCreate(['name' => $item['name']], [
                'category_id'      => $catModels[$item['cat']]->id,
                'price'            => $item['price'],
                'cost_price'       => $item['cost_price'],
                'preparation_time' => $item['prep'],
                'is_available'     => true,
                'is_featured'      => $item['featured'],
                'stock_quantity'   => -1,
            ]);
        }

        // Restaurant tables
        $tables = [
            ['T01', 'Table 1',   2, 'Indoor'],
            ['T02', 'Table 2',   4, 'Indoor'],
            ['T03', 'Table 3',   4, 'Indoor'],
            ['T04', 'Table 4',   6, 'Indoor'],
            ['T05', 'Table 5',   4, 'Outdoor'],
            ['T06', 'Table 6',   4, 'Outdoor'],
            ['T07', 'Table 7',   8, 'Outdoor'],
            ['T08', 'VIP Room',  10, 'Private'],
            ['T09', 'Bar Seat 1', 1, 'Bar'],
            ['T10', 'Bar Seat 2', 1, 'Bar'],
        ];

        $tableModels = [];
        foreach ($tables as [$num, $name, $cap, $loc]) {
            $tableModels[] = RestaurantTable::firstOrCreate(['table_number' => $num], [
                'name' => $name, 'capacity' => $cap, 'location' => $loc, 'status' => 'available',
            ]);
        }

        // Customers
        $customers = [
            ['Alice Johnson', 'alice@example.com', '+1 555-0101'],
            ['Bob Smith',     'bob@example.com',   '+1 555-0102'],
            ['Carol White',   'carol@example.com', '+1 555-0103'],
            ['David Brown',   'david@example.com', '+1 555-0104'],
            ['Emma Davis',    'emma@example.com',  '+1 555-0105'],
        ];

        $customerModels = [];
        foreach ($customers as [$name, $email, $phone]) {
            $customerModels[] = Customer::firstOrCreate(['email' => $email], [
                'name' => $name, 'phone' => $phone, 'is_active' => true,
            ]);
        }

        // Sample orders
        $statuses      = ['completed', 'completed', 'completed', 'pending', 'preparing'];
        $paymentTypes  = ['cash', 'card', 'online'];
        $orderTypes    = ['dine_in', 'takeaway', 'dine_in', 'dine_in', 'delivery'];

        if (Order::count() === 0) {
            foreach (range(1, 20) as $i) {
                $customer = $customerModels[array_rand($customerModels)];
                $table    = $tableModels[array_rand($tableModels)];
                $status   = $statuses[array_rand($statuses)];
                $items    = collect($menuModels)->random(rand(1, 4));

                $subTotal = 0;
                $lineItems = [];
                foreach ($items as $mi) {
                    $qty = rand(1, 3);
                    $sub = round($mi->price * $qty, 2);
                    $subTotal += $sub;
                    $lineItems[] = ['menu_item' => $mi, 'qty' => $qty, 'sub' => $sub];
                }

                $vat      = round($subTotal * 0.1, 2);
                $total    = $subTotal + $vat;
                $pay      = $status === 'completed' ? $total : 0;
                $due      = max(0, $total - $pay);
                $payType  = $paymentTypes[array_rand($paymentTypes)];

                $order = Order::create([
                    'invoice_no'     => 'INV-' . strtoupper(Str::random(8)),
                    'table_id'       => $table->id,
                    'customer_id'    => $customer->id,
                    'user_id'        => $user->id,
                    'order_type'     => $orderTypes[array_rand($orderTypes)],
                    'order_status'   => $status,
                    'payment_status' => $pay >= $total ? 'paid' : 'unpaid',
                    'payment_type'   => $payType,
                    'sub_total'      => $subTotal,
                    'vat'            => $vat,
                    'discount'       => 0,
                    'total'          => $total,
                    'pay_amount'     => $pay,
                    'due_amount'     => $due,
                    'created_at'     => now()->subDays(rand(0, 30))->subHours(rand(0, 12)),
                ]);

                foreach ($lineItems as $li) {
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'menu_item_id' => $li['menu_item']->id,
                        'quantity'     => $li['qty'],
                        'unit_price'   => $li['menu_item']->price,
                        'subtotal'     => $li['sub'],
                    ]);
                }

                // Update customer stats
                $customer->increment('visit_count');
                if ($status === 'completed') {
                    $customer->increment('total_spent', $total);
                }
            }
        }
    }
}
