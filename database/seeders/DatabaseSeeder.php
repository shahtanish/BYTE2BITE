<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\FoodItem;
use App\Models\DeliveryPartner;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderRestaurantStatus;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── ADMIN ──────────────────────────────────────────────
        User::create([
            'name'        => 'Super Admin',
            'email'       => 'admin@byte2bite.com',
            'phone'       => '9000000001',
            'password'    => Hash::make('admin123'),
            'role'        => 'admin',
            'is_active'   => true,
            'is_approved' => true,
        ]);

        // ─── CATEGORIES ─────────────────────────────────────────
        $categories = [
            ['name'=>'Burgers',      'slug'=>'burgers'],
            ['name'=>'Pizza',        'slug'=>'pizza'],
            ['name'=>'Biryani',      'slug'=>'biryani'],
            ['name'=>'Chinese',      'slug'=>'chinese'],
            ['name'=>'South Indian', 'slug'=>'south-indian'],
            ['name'=>'North Indian', 'slug'=>'north-indian'],
            ['name'=>'Desserts',     'slug'=>'desserts'],
            ['name'=>'Beverages',    'slug'=>'beverages'],
        ];
        foreach ($categories as $i => $cat) {
            Category::create(array_merge($cat, ['is_active'=>true,'sort_order'=>$i+1]));
        }
        $cats = Category::all()->keyBy('slug');

        // ─── RESTAURANT 1 ───────────────────────────────────────
        $r1user = User::create([
            'name'=>'Spice Garden Owner','email'=>'spice@byte2bite.com','phone'=>'9000000010',
            'password'=>Hash::make('restaurant123'),'role'=>'restaurant','is_active'=>true,'is_approved'=>true,
        ]);
        $r1 = Restaurant::create([
            'user_id'=>$r1user->id,'name'=>'Spice Garden','slug'=>'spice-garden-abc1',
            'description'=>'Authentic North & South Indian cuisine with 20 years of culinary tradition.',
            'phone'=>'9000000010','email'=>'spice@byte2bite.com',
            'address'=>'12 MG Road','city'=>'Bangalore','state'=>'Karnataka','pincode'=>'560001',
            'latitude'=>12.9716,'longitude'=>77.5946,
            'cuisine_type'=>'Multi-Cuisine','opening_time'=>'09:00','closing_time'=>'23:00',
            'delivery_fee'=>30,'min_order_amount'=>100,'avg_delivery_time'=>35,
            'rating'=>4.3,'total_reviews'=>128,'status'=>'approved','is_open'=>true,'is_active'=>true,
        ]);
        $this->seedMenu($r1, $cats, [
            ['name'=>'Butter Chicken','cat'=>'north-indian','price'=>220,'type'=>'non_veg','featured'=>true,'desc'=>'Creamy tomato-based curry with tender chicken pieces'],
            ['name'=>'Dal Makhani','cat'=>'north-indian','price'=>160,'type'=>'veg','desc'=>'Slow-cooked black lentils in rich buttery gravy'],
            ['name'=>'Paneer Tikka','cat'=>'north-indian','price'=>180,'type'=>'veg','featured'=>true,'desc'=>'Grilled cottage cheese marinated in spiced yogurt'],
            ['name'=>'Masala Dosa','cat'=>'south-indian','price'=>80,'type'=>'veg','desc'=>'Crispy rice crepe with spiced potato filling'],
            ['name'=>'Idli Sambar','cat'=>'south-indian','price'=>60,'type'=>'veg','desc'=>'Steamed rice cakes served with sambar and chutneys'],
            ['name'=>'Gulab Jamun','cat'=>'desserts','price'=>60,'type'=>'veg','desc'=>'Soft milk solid balls soaked in rose sugar syrup'],
            ['name'=>'Mango Lassi','cat'=>'beverages','price'=>80,'type'=>'veg','desc'=>'Chilled mango and yogurt drink'],
        ]);

        // ─── RESTAURANT 2 ───────────────────────────────────────
        $r2user = User::create([
            'name'=>'Burger Bliss Owner','email'=>'burger@byte2bite.com','phone'=>'9000000020',
            'password'=>Hash::make('restaurant123'),'role'=>'restaurant','is_active'=>true,'is_approved'=>true,
        ]);
        $r2 = Restaurant::create([
            'user_id'=>$r2user->id,'name'=>'Burger Bliss','slug'=>'burger-bliss-abc2',
            'description'=>'Gourmet burgers made with 100% fresh ingredients. Voted best burgers in the city.',
            'phone'=>'9000000020','email'=>'burger@byte2bite.com',
            'address'=>'45 Koramangala','city'=>'Bangalore','state'=>'Karnataka','pincode'=>'560034',
            'latitude'=>12.9352,'longitude'=>77.6245,
            'cuisine_type'=>'Fast Food','opening_time'=>'10:00','closing_time'=>'23:30',
            'delivery_fee'=>25,'min_order_amount'=>150,'avg_delivery_time'=>25,
            'rating'=>4.6,'total_reviews'=>345,'status'=>'approved','is_open'=>true,'is_active'=>true,
        ]);
        $this->seedMenu($r2, $cats, [
            ['name'=>'Classic Beef Burger','cat'=>'burgers','price'=>199,'type'=>'non_veg','featured'=>true,'desc'=>'Juicy beef patty with lettuce, tomato, cheese and special sauce'],
            ['name'=>'Chicken Crispy Burger','cat'=>'burgers','price'=>179,'type'=>'non_veg','desc'=>'Crispy fried chicken with coleslaw and mayo'],
            ['name'=>'Veggie Delight Burger','cat'=>'burgers','price'=>149,'type'=>'veg','desc'=>'Aloo tikki patty with fresh veggies and mint chutney'],
            ['name'=>'Double Smash Burger','cat'=>'burgers','price'=>249,'type'=>'non_veg','featured'=>true,'desc'=>'Two smashed beef patties with double cheese'],
            ['name'=>'Loaded Fries','cat'=>'burgers','price'=>99,'type'=>'veg','desc'=>'Crispy fries topped with cheese sauce and jalapeños'],
            ['name'=>'Chocolate Shake','cat'=>'beverages','price'=>119,'type'=>'veg','desc'=>'Rich thick chocolate milkshake'],
        ]);

        // ─── RESTAURANT 3 ───────────────────────────────────────
        $r3user = User::create([
            'name'=>'Pizza Planet Owner','email'=>'pizza@byte2bite.com','phone'=>'9000000030',
            'password'=>Hash::make('restaurant123'),'role'=>'restaurant','is_active'=>true,'is_approved'=>true,
        ]);
        $r3 = Restaurant::create([
            'user_id'=>$r3user->id,'name'=>'Pizza Planet','slug'=>'pizza-planet-abc3',
            'description'=>'New York style hand-tossed pizzas baked in wood-fired ovens.',
            'phone'=>'9000000030','email'=>'pizza@byte2bite.com',
            'address'=>'78 Indiranagar','city'=>'Bangalore','state'=>'Karnataka','pincode'=>'560038',
            'latitude'=>12.9784,'longitude'=>77.6408,
            'cuisine_type'=>'Pizza','opening_time'=>'11:00','closing_time'=>'23:00',
            'delivery_fee'=>40,'min_order_amount'=>200,'avg_delivery_time'=>40,
            'rating'=>4.4,'total_reviews'=>212,'status'=>'approved','is_open'=>true,'is_active'=>true,
        ]);
        $this->seedMenu($r3, $cats, [
            ['name'=>'Margherita Pizza','cat'=>'pizza','price'=>249,'type'=>'veg','featured'=>true,'desc'=>'Classic tomato, fresh mozzarella and basil'],
            ['name'=>'Pepperoni Feast','cat'=>'pizza','price'=>349,'type'=>'non_veg','featured'=>true,'desc'=>'Loaded with premium pepperoni and mozzarella'],
            ['name'=>'BBQ Chicken Pizza','cat'=>'pizza','price'=>329,'type'=>'non_veg','desc'=>'Smoky BBQ chicken, red onion, and cheddar'],
            ['name'=>'Farm House Pizza','cat'=>'pizza','price'=>299,'type'=>'veg','desc'=>'Capsicum, mushroom, corn, olives on tomato base'],
            ['name'=>'Pasta Arrabbiata','cat'=>'pizza','price'=>199,'type'=>'veg','desc'=>'Penne in spicy tomato sauce with herbs'],
            ['name'=>'Garlic Bread','cat'=>'pizza','price'=>99,'type'=>'veg','desc'=>'Toasted garlic butter bread with herbs'],
            ['name'=>'Tiramisu','cat'=>'desserts','price'=>149,'type'=>'veg','desc'=>'Classic Italian coffee-flavoured dessert'],
        ]);

        // ─── DELIVERY PARTNERS ──────────────────────────────────
        $dp1user = User::create([
            'name'=>'Raj Kumar','email'=>'raj@byte2bite.com','phone'=>'9111000001',
            'password'=>Hash::make('delivery123'),'role'=>'delivery','is_active'=>true,'is_approved'=>true,
        ]);
        DeliveryPartner::create([
            'user_id'=>$dp1user->id,'vehicle_type'=>'Bike','vehicle_number'=>'KA01AB1234',
            'license_number'=>'KA0120210001','total_deliveries'=>145,'earnings_total'=>4350,'rating'=>4.7,'is_available'=>true,
        ]);

        $dp2user = User::create([
            'name'=>'Priya Singh','email'=>'priya@byte2bite.com','phone'=>'9111000002',
            'password'=>Hash::make('delivery123'),'role'=>'delivery','is_active'=>true,'is_approved'=>true,
        ]);
        DeliveryPartner::create([
            'user_id'=>$dp2user->id,'vehicle_type'=>'Scooter','vehicle_number'=>'KA02CD5678',
            'license_number'=>'KA0220200002','total_deliveries'=>89,'earnings_total'=>2670,'rating'=>4.5,'is_available'=>true,
        ]);

        // ─── CUSTOMERS ──────────────────────────────────────────
        $customers = [];
        $custData = [
            ['name'=>'Aarav Sharma',  'email'=>'aarav@example.com',  'phone'=>'9800000001'],
            ['name'=>'Priya Patel',   'email'=>'priya@example.com',  'phone'=>'9800000002'],
            ['name'=>'Rahul Verma',   'email'=>'rahul@example.com',  'phone'=>'9800000003'],
        ];
        foreach ($custData as $cd) {
            $customers[] = User::create(array_merge($cd, [
                'password'=>Hash::make('customer123'),'role'=>'customer',
                'address'=>'123 Main Street','city'=>'Bangalore',
                'is_active'=>true,'is_approved'=>true,
            ]));
        }

        // ─── SAMPLE ORDERS ──────────────────────────────────────
        $this->seedOrder($customers[0], $r1, [$r1->foodItems->first(), $r1->foodItems->get(1)], 'delivered', $dp1user->id);
        $this->seedOrder($customers[1], $r2, [$r2->foodItems->first(), $r2->foodItems->get(2)], 'on_the_way',  $dp2user->id);
        $this->seedOrder($customers[2], $r3, [$r3->foodItems->first()], 'preparing', null);
        $this->seedOrder($customers[0], $r2, [$r2->foodItems->get(1), $r2->foodItems->get(3)], 'pending', null);

        $this->command->info('✅ BYTE2BITE demo data seeded!');
        $this->command->info('Admin:    admin@byte2bite.com / admin123');
        $this->command->info('Restaurant: spice@byte2bite.com / restaurant123');
        $this->command->info('Delivery: raj@byte2bite.com / delivery123');
        $this->command->info('Customer: aarav@example.com / customer123');
    }

    private function seedMenu(Restaurant $rest, $cats, array $items): void
    {
        foreach ($items as $i => $item) {
            FoodItem::create([
                'restaurant_id'    => $rest->id,
                'category_id'      => $cats[$item['cat']]->id,
                'name'             => $item['name'],
                'slug'             => Str::slug($item['name']).'-'.Str::random(4),
                'description'      => $item['desc'] ?? '',
                'price'            => $item['price'],
                'food_type'        => $item['type'],
                'is_available'     => true,
                'is_featured'      => $item['featured'] ?? false,
                'preparation_time' => rand(15,35),
                'sort_order'       => $i + 1,
            ]);
        }
    }

    private function seedOrder(User $customer, Restaurant $restaurant, $items, string $status, ?int $deliveryPartnerId): void
    {
        $subtotal = 0;
        foreach ($items as $item) { $subtotal += $item->price; }
        $deliveryFee = $restaurant->delivery_fee;
        $tax   = round($subtotal * 0.05, 2);
        $total = $subtotal + $deliveryFee + $tax;

        $order = Order::create([
            'order_number'       => Order::generateOrderNumber(),
            'user_id'            => $customer->id,
            'delivery_partner_id'=> $deliveryPartnerId,
            'delivery_name'      => $customer->name,
            'delivery_phone'     => $customer->phone,
            'delivery_address'   => $customer->address ?? '123 Main Street',
            'delivery_city'      => $customer->city ?? 'Bangalore',
            'delivery_pincode'   => '560001',
            'subtotal'           => $subtotal,
            'delivery_fee'       => $deliveryFee,
            'tax'                => $tax,
            'total'              => $total,
            'payment_method'     => rand(0,1) ? 'cod' : 'online',
            'payment_status'     => $status === 'delivered' ? 'paid' : 'pending',
            'status'             => $status,
            'accepted_at'        => in_array($status,['preparing','on_the_way','delivered']) ? now()->subHours(2) : null,
            'picked_up_at'       => in_array($status,['on_the_way','delivered']) ? now()->subHour() : null,
            'delivered_at'       => $status === 'delivered' ? now()->subMinutes(30) : null,
            'current_latitude'   => 12.9716,
            'current_longitude'  => 77.5946,
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'      => $order->id,
                'restaurant_id' => $restaurant->id,
                'food_item_id'  => $item->id,
                'food_name'     => $item->name,
                'food_price'    => $item->price,
                'quantity'      => 1,
                'subtotal'      => $item->price,
            ]);
        }

        OrderRestaurantStatus::create([
            'order_id'      => $order->id,
            'restaurant_id' => $restaurant->id,
            'status'        => match($status) {
                'delivered','on_the_way','picked_up' => 'ready',
                'preparing' => 'preparing',
                'accepted'  => 'accepted',
                default     => 'pending',
            },
            'accepted_at' => in_array($status,['preparing','on_the_way','delivered']) ? now()->subHours(2) : null,
            'ready_at'    => in_array($status,['on_the_way','delivered']) ? now()->subHours(1) : null,
        ]);
    }
}
