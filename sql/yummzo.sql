-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2025 at 01:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yummzo`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_surplus` tinyint(1) DEFAULT 0,
  `category` varchar(50) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `restaurant_id`, `item_name`, `description`, `price`, `is_surplus`, `category`, `is_available`) VALUES
(1, 1, 'Paneer Butter Masala', 'Creamy cottage cheese curry', 280.00, 0, 'Main Course', 1),
(2, 1, 'Butter Naan', 'Clay oven baked bread', 40.00, 0, 'Bread', 1),
(3, 1, 'Dal Makhani', 'Black lentils slow cooked', 220.00, 0, 'Main Course', 1),
(4, 1, 'Surplus Curry Box', 'Discounted lunch leftovers', 99.00, 1, 'Main Course', 1),
(5, 1, 'Jeera Rice', 'Cumin tempered rice', 150.00, 0, 'Rice', 1),
(6, 2, 'Chicken Biryani', 'Hyderabadi style aromatic rice', 320.00, 0, 'Main Course', 1),
(7, 2, 'Veg Biryani', 'Mixed veg aromatic rice', 250.00, 0, 'Main Course', 1),
(8, 2, 'Raita', 'Cooling yogurt dip', 50.00, 0, 'Side', 1),
(9, 2, 'Surplus Rice Portion', 'Evening discount rice', 70.00, 1, 'Main Course', 1),
(10, 4, 'Margherita Pizza', 'Classic cheese and basil', 350.00, 0, 'Main Course', 1),
(11, 4, 'Pepperoni Feast', 'Double pepperoni with cheese', 450.00, 0, 'Main Course', 1),
(12, 4, 'Garlic Bread', 'Buttery garlic sticks', 120.00, 0, 'Side', 1),
(13, 4, 'Surplus Dough Bites', 'Bread bites from leftover dough', 45.00, 1, 'Side', 1),
(14, 5, 'Alfredo Pasta', 'White sauce creamy pasta', 290.00, 0, 'Main Course', 1),
(15, 7, 'Hakka Noodles', 'Stir fried veg noodles', 210.00, 0, 'Main Course', 1),
(16, 7, 'Manchurian', 'Deep fried veg balls in gravy', 190.00, 0, 'Side', 1),
(17, 10, 'Veggie Burger', 'Grilled veg patty burger', 140.00, 0, 'Main Course', 1),
(18, 13, 'Quinoa Salad', 'Superfood healthy salad', 320.00, 0, 'Healthy', 1),
(19, 13, 'Fruit Platter', 'Seasonal fresh fruits', 180.00, 0, 'Healthy', 1),
(20, 18, 'Chocolate Waffle', 'Belgian waffle with syrup', 160.00, 0, 'Dessert', 1),
(21, 24, 'Belgian Waffle', 'Classic honey waffle', 150.00, 0, 'Dessert', 1),
(22, 26, 'Masala Chai', 'Indian spiced tea', 30.00, 0, 'Beverages', 1),
(23, 26, 'Green Tea', 'Healthy organic tea', 45.00, 0, 'Beverages', 1),
(24, 27, 'California Roll', 'Crab and avocado sushi', 450.00, 0, 'Main Course', 1),
(25, 27, 'Miso Soup', 'Traditional Japanese soup', 120.00, 0, 'Side', 1),
(26, 28, 'Chicken Tacos', 'Spicy Mexican tacos', 280.00, 0, 'Starters', 1),
(27, 28, 'Veg Quesadilla', 'Cheese and veg filled', 320.00, 0, 'Main Course', 1),
(28, 29, 'Grilled Steak', 'Juicy beef steak', 850.00, 0, 'Main Course', 1),
(29, 30, 'Mixed Fruit Bowl', 'Assorted fresh fruits', 150.00, 1, 'Healthy', 1),
(30, 1, 'Samosa Platter', '4 pieces with chutney', 80.00, 1, 'Starters', 1),
(31, 4, 'Cheese Garlic Bread', 'Extra cheesy sticks', 180.00, 0, 'Side', 1),
(32, 10, 'Double Patty Burger', 'Extra large veg burger', 220.00, 0, 'Main Course', 1),
(33, 13, 'Detox Juice', 'Cold pressed green juice', 120.00, 0, 'Beverages', 1),
(34, 2, 'Chicken Korma', 'Rich almond gravy chicken', 340.00, 0, 'Main Course', 1),
(35, 2, 'Garlic Naan', 'Tandoori bread with garlic', 55.00, 0, 'Bread', 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `order_id`, `sender_id`, `message`, `timestamp`) VALUES
(1, 1, 1, 'Please leave the food at the gate.', '2025-12-18 04:30:00'),
(2, 1, 11, 'Sure, I have placed it. Enjoy your meal!', '2025-12-18 04:35:00'),
(3, 2, 2, 'Is the food ready? I am in a bit of a hurry.', '2025-12-18 05:00:00'),
(4, 2, 12, 'The restaurant is just packing it. I will pick it up in 2 mins.', '2025-12-18 05:02:00'),
(5, 3, 3, 'Can you bring some extra napkins?', '2025-12-18 05:30:00'),
(6, 4, 14, 'I have picked up your order and am on my way.', '2025-12-18 05:45:00'),
(7, 4, 4, 'Great! Please call me when you reach the main gate.', '2025-12-18 05:46:00'),
(8, 5, 5, 'Please do not ring the bell, the baby is sleeping.', '2025-12-18 06:15:00'),
(9, 5, 15, 'Got it. I will call you instead.', '2025-12-18 06:17:00'),
(10, 6, 1, 'The location on map is slightly wrong, look for the blue gate.', '2025-12-18 06:35:00'),
(11, 6, 11, 'Okay, I am near the blue gate now.', '2025-12-18 06:40:00'),
(12, 7, 2, 'Wait, I forgot to add cutlery. Can you ask them?', '2025-12-18 06:45:00'),
(13, 7, 12, 'I have already left the restaurant, sorry!', '2025-12-18 06:47:00'),
(14, 10, 5, 'Is it raining there? Please drive safely.', '2025-12-18 07:15:00'),
(15, 10, 15, 'Yes, a little bit. I will be there in 10 mins.', '2025-12-18 07:17:00'),
(16, 17, 12, 'Sir, your apartment lift is not working. Coming by stairs.', '2025-12-18 07:45:00'),
(17, 17, 2, 'Oh, so sorry about that! I am on the 2nd floor.', '2025-12-18 07:46:00'),
(18, 21, 1, 'The drink is cold, right?', '2025-12-18 08:00:00'),
(19, 25, 5, 'Almost there?', '2025-12-18 08:30:00'),
(20, 25, 15, 'Yes, just turning into your street now.', '2025-12-18 08:32:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `restaurant_id` int(11) DEFAULT NULL,
  `delivery_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','preparing','out_for_delivery','delivered','cancelled') DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_received` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `restaurant_id`, `delivery_id`, `total_amount`, `status`, `order_date`, `payment_received`) VALUES
(1, 1, 1, 11, 320.00, 'delivered', '2025-12-17 14:55:46', 0),
(2, 2, 4, 12, 470.00, 'preparing', '2025-12-17 14:55:46', 0),
(3, 3, 7, 13, 400.00, 'pending', '2025-12-17 14:55:46', 0),
(4, 4, 10, 14, 140.00, 'out_for_delivery', '2025-12-17 14:55:46', 0),
(5, 5, 13, 15, 320.00, 'pending', '2025-12-17 14:55:46', 0),
(6, 1, 26, 11, 60.00, 'delivered', '2025-12-18 03:30:00', 0),
(7, 2, 1, 12, 320.00, 'delivered', '2025-12-18 03:45:00', 0),
(8, 3, 4, 13, 530.00, 'preparing', '2025-12-18 05:00:00', 0),
(9, 4, 10, 14, 220.00, 'pending', '2025-12-18 05:30:00', 0),
(10, 5, 27, 15, 570.00, 'out_for_delivery', '2025-12-18 06:15:00', 0),
(11, 1, 2, 11, 395.00, 'preparing', '2025-12-18 06:30:00', 0),
(12, 2, 28, 12, 600.00, 'pending', '2025-12-18 06:40:00', 0),
(13, 3, 13, 13, 120.00, 'delivered', '2025-12-18 06:45:00', 0),
(14, 4, 29, 14, 850.00, 'delivered', '2025-12-18 06:50:00', 0),
(15, 5, 30, 15, 150.00, 'out_for_delivery', '2025-12-18 06:55:00', 0),
(16, 1, 7, 11, 400.00, 'delivered', '2025-12-18 07:00:00', 0),
(17, 2, 6, 12, 900.00, 'cancelled', '2025-12-18 07:05:00', 0),
(18, 3, 18, 13, 160.00, 'delivered', '2025-12-18 07:10:00', 0),
(19, 4, 24, 14, 150.00, 'preparing', '2025-12-18 07:15:00', 0),
(20, 5, 1, 15, 280.00, 'pending', '2025-12-18 07:20:00', 0),
(21, 1, 4, 11, 470.00, 'delivered', '2025-12-18 07:25:00', 0),
(22, 2, 10, 12, 140.00, 'out_for_delivery', '2025-12-18 07:30:00', 0),
(23, 3, 13, 13, 320.00, 'delivered', '2025-12-18 07:35:00', 0),
(24, 4, 2, 14, 320.00, 'preparing', '2025-12-18 07:40:00', 0),
(25, 5, 28, 15, 320.00, 'pending', '2025-12-18 07:45:00', 0),
(26, 1, 26, 11, 90.00, 'delivered', '2025-12-18 07:50:00', 0),
(27, 2, 27, 12, 450.00, 'delivered', '2025-12-18 07:55:00', 0),
(28, 3, 29, 13, 1700.00, 'preparing', '2025-12-18 08:00:00', 0),
(29, 4, 30, 14, 300.00, 'pending', '2025-12-18 08:05:00', 0),
(30, 5, 1, 15, 120.00, 'out_for_delivery', '2025-12-18 08:10:00', 0),
(31, 1, 2, 11, 680.00, 'delivered', '2025-12-18 08:15:00', 0),
(32, 2, 4, 12, 350.00, 'delivered', '2025-12-18 08:20:00', 0),
(33, 3, 10, 13, 440.00, 'preparing', '2025-12-18 08:25:00', 0),
(34, 4, 13, 14, 180.00, 'pending', '2025-12-18 08:30:00', 0),
(35, 5, 7, 15, 210.00, 'out_for_delivery', '2025-12-18 08:35:00', 0),
(36, 1, 1, 11, 450.00, 'delivered', '2025-12-18 08:40:00', 0),
(37, 2, 2, 12, 320.00, 'delivered', '2025-12-18 08:45:00', 0),
(38, 3, 4, 13, 450.00, 'preparing', '2025-12-18 08:50:00', 0),
(39, 4, 10, 14, 140.00, 'pending', '2025-12-18 08:55:00', 0),
(40, 5, 13, 15, 320.00, 'out_for_delivery', '2025-12-18 09:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `restaurant_name` varchar(100) NOT NULL,
  `cuisine_type` varchar(50) DEFAULT NULL,
  `health_rating` decimal(2,1) DEFAULT 5.0,
  `status` enum('open','closed') DEFAULT 'open',
  `open_time` time DEFAULT '09:00:00',
  `close_time` time DEFAULT '22:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `user_id`, `restaurant_name`, `cuisine_type`, `health_rating`, `status`, `open_time`, `close_time`) VALUES
(1, 6, 'The Spice Route', 'Indian', 4.8, 'open', '09:00:00', '22:00:00'),
(2, 6, 'Biryani King', 'Indian', 4.5, 'open', '09:00:00', '22:00:00'),
(3, 6, 'Tandoori Nights', 'Indian', 4.2, 'open', '09:00:00', '22:00:00'),
(4, 7, 'Napoli Pizza', 'Italian', 4.7, 'open', '09:00:00', '22:00:00'),
(5, 7, 'Pasta Palace', 'Italian', 4.4, 'open', '09:00:00', '22:00:00'),
(6, 7, 'Little Italy', 'Italian', 4.9, 'open', '09:00:00', '22:00:00'),
(7, 8, 'Dragon Wok', 'Chinese', 4.1, 'open', '09:00:00', '22:00:00'),
(8, 8, 'Dimsum House', 'Chinese', 4.3, 'open', '09:00:00', '22:00:00'),
(9, 8, 'Red Chilli', 'Chinese', 3.9, 'open', '09:00:00', '22:00:00'),
(10, 9, 'Burger Point', 'Fast Food', 4.6, 'open', '09:00:00', '22:00:00'),
(11, 9, 'Crispy Fry', 'Fast Food', 4.0, 'open', '09:00:00', '22:00:00'),
(12, 9, 'Sub Station', 'Fast Food', 4.2, 'open', '09:00:00', '22:00:00'),
(13, 10, 'Green Bowl', 'Healthy', 5.0, 'open', '09:00:00', '22:00:00'),
(14, 10, 'Salad Days', 'Healthy', 4.8, 'open', '09:00:00', '22:00:00'),
(15, 10, 'Vegan Vibes', 'Healthy', 4.7, 'open', '09:00:00', '22:00:00'),
(16, 6, 'South Treat', 'South Indian', 4.5, 'open', '09:00:00', '22:00:00'),
(17, 6, 'Idli Express', 'South Indian', 4.4, 'open', '09:00:00', '22:00:00'),
(18, 7, 'Sweet Tooth', 'Dessert', 4.9, 'open', '09:00:00', '22:00:00'),
(19, 7, 'Choco Lava', 'Dessert', 4.7, 'open', '09:00:00', '22:00:00'),
(20, 8, 'The Grill House', 'North Indian', 4.6, 'open', '09:00:00', '22:00:00'),
(21, 8, 'Punjabi Dhaba', 'North Indian', 4.2, 'open', '09:00:00', '22:00:00'),
(22, 9, 'Kebab Korner', 'North Indian', 4.5, 'open', '09:00:00', '22:00:00'),
(23, 9, 'Mughlai Magic', 'North Indian', 4.3, 'open', '09:00:00', '22:00:00'),
(24, 10, 'Waffle World', 'Dessert', 4.8, 'open', '09:00:00', '22:00:00'),
(25, 10, 'Ice Creamery', 'Dessert', 4.6, 'open', '09:00:00', '22:00:00'),
(26, 6, 'The Tea House', 'Beverages', 4.5, 'open', '09:00:00', '22:00:00'),
(27, 7, 'Sushi Zen', 'Japanese', 4.8, 'open', '09:00:00', '22:00:00'),
(28, 8, 'Mexican Wave', 'Mexican', 4.3, 'open', '09:00:00', '22:00:00'),
(29, 9, 'Steak House', 'Continental', 4.1, 'open', '09:00:00', '22:00:00'),
(30, 10, 'Fruit Fusion', 'Healthy', 4.9, 'open', '09:00:00', '22:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('customer','restaurant','delivery') NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `role`, `address`, `created_at`) VALUES
(1, 'Amit Sharma', 'amit@gmail.com', '123456', '9876543210', 'customer', 'Flat 101, Sunshine Apts, Mumbai', '2025-12-17 14:55:46'),
(2, 'Sneha Reddy', 'sneha@gmail.com', '123456', '9876543211', 'customer', 'House 45, Jubilee Hills, Hyderabad', '2025-12-17 14:55:46'),
(3, 'John Doe', 'john@gmail.com', '123456', '9876543212', 'customer', 'Street 7, Indiranagar, Bangalore', '2025-12-17 14:55:46'),
(4, 'Priya Verma', 'priya@gmail.com', '123456', '9876543213', 'customer', 'Sector 15, Gurgaon', '2025-12-17 14:55:46'),
(5, 'Rahul Gupta', 'rahul@gmail.com', '123456', '9876543214', 'customer', 'Civil Lines, Jaipur', '2025-12-17 14:55:46'),
(6, 'Alice Owner', 'owner1@yummzo.com', '123456', '9111111111', 'restaurant', 'Bandra West', '2025-12-17 14:55:46'),
(7, 'Bob Chef', 'owner2@yummzo.com', '123456', '9222222222', 'restaurant', 'Koramangala', '2025-12-17 14:55:46'),
(8, 'Charlie Food', 'owner3@yummzo.com', '123456', '9333333333', 'restaurant', 'Connaught Place', '2025-12-17 14:55:46'),
(9, 'David Dine', 'owner4@yummzo.com', '123456', '9444444444', 'restaurant', 'Salt Lake', '2025-12-17 14:55:46'),
(10, 'Esha Eats', 'owner5@yummzo.com', '123456', '9555555555', 'restaurant', 'Anna Nagar', '2025-12-17 14:55:46'),
(11, 'Rider One', 'rider1@yummzo.com', '123456', '8111111111', 'delivery', 'Zone A Hub', '2025-12-17 14:55:46'),
(12, 'Rider Two', 'rider2@yummzo.com', '123456', '8222222222', 'delivery', 'Zone B Hub', '2025-12-17 14:55:46'),
(13, 'Rider Three', 'rider3@yummzo.com', '123456', '8333333333', 'delivery', 'Zone C Hub', '2025-12-17 14:55:46'),
(14, 'Rider Four', 'rider4@yummzo.com', '123456', '8444444444', 'delivery', 'Zone D Hub', '2025-12-17 14:55:46'),
(15, 'Rider Five', 'rider5@yummzo.com', '123456', '8555555555', 'delivery', 'Zone E Hub', '2025-12-17 14:55:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `delivery_id` (`delivery_id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`delivery_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `restaurants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
