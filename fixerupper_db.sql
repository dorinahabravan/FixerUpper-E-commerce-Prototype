-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306:3306
-- Generation Time: Jul 04, 2025 at 02:49 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fixerupper_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `customer_email`, `address`, `total`, `created_at`) VALUES
(24, 13, 'Dorina Habravan', 'dorina.habravan@gmail.com', '74 Fabrick House, Leicester', 239.00, '2025-07-03 21:08:49');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(21, 24, 5, 1, 239.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `date_added` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`, `quantity`, `date_added`) VALUES
(1, 'ASUS Wi-Fi 6 AX Router – Dual-Band Gigabit MU-MIMO', '✔️ Wi-Fi Standard: Wi-Fi 6 (802.11ax)\n✔️ Bands: Dual-Band 2.4GHz & 5GHz\n✔️ Speed: Up to 3000 Mbps\n✔️ Ports: 4x Gigabit LAN, 1x WAN\n✔️ Features: MU-MIMO, OFDMA, Beamforming\n✔️ Security: WPA3 Encryption\n✔️ App Support: ASUS Router App\n✔️ Warranty: 12 Months', 'router.jpg', 89.99, 12, '2025-06-26'),
(2, 'Samsung 1TB NVMe SSD – PCIe Gen3 x4 High-Speed Storage', '✔️ Capacity: 1TB NVMe\r\n✔️ Interface: PCIe Gen3 x4\r\n✔️ Sequential Read: Up to 3500 MB/s\r\n✔️ Sequential Write: Up to 3000 MB/s\r\n✔️ Form Factor: M.2 2280\r\n✔️ Endurance: 600 TBW\r\n✔️ Encryption: AES 256-bit\r\n✔️ Warranty: 5 Years', 'ssd.jpg', 109.50, 15, '2025-06-26'),
(3, 'Dell 27″ FHD Monitor – Ultra-Thin Bezel IPS Display', '✔️ Size: 27-inch Full HD (1920x1080)\r\n✔️ Panel: IPS with Ultra-Thin Bezels\r\n✔️ Refresh Rate: 75 Hz\r\n✔️ Response Time: 5ms (GTG)\r\n✔️ Connectivity: HDMI, DisplayPort, VGA\r\n✔️ Adjustable Stand: Tilt\r\n✔️ VESA Mount Compatible\r\n✔️ Warranty: 3 Years', 'monitor.jpg', 179.99, 8, '2025-06-26'),
(4, 'Logitech MX Master 3 – Ergonomic Wireless Bluetooth Mouse', '✔️ Connectivity: Wireless (Bluetooth / USB Receiver)\r\n✔️ DPI: Up to 4000 DPI Precision\r\n✔️ Battery Life: 70 Days Per Charge\r\n✔️ Buttons: 7 Customizable\r\n✔️ Features: MagSpeed Scroll, App-Specific Customization\r\n✔️ Compatibility: Windows, macOS, Linux\r\n✔️ Charging: USB-C\r\n✔️ Warranty: 1 Year', 'mouse.jpg', 79.99, 20, '2025-06-26'),
(5, 'Intel Core i7 11th Gen – 8 Cores with UHD Graphics', '✔️ Generation: 11th Gen Intel Core i7\r\n✔️ Cores/Threads: 8 Cores / 16 Threads\r\n✔️ Base Clock: 2.8 GHz\r\n✔️ Turbo Boost: Up to 4.7 GHz\r\n✔️ Integrated Graphics: Intel UHD 750\r\n✔️ Socket: LGA1200\r\n✔️ TDP: 65W\r\n✔️ Warranty: 3 Years', 'cpu.jpg', 239.00, 5, '2025-06-26'),
(6, 'Corsair 16GB DDR4 3200MHz – High-Performance Dual Kit', '✔️ Capacity: 16GB (2x8GB Kit)\r\n✔️ Type: DDR4\r\n✔️ Speed: 3200 MHz\r\n✔️ CAS Latency: CL16\r\n✔️ Voltage: 1.35V\r\n✔️ Compatibility: Intel & AMD Platforms\r\n✔️ Heatspreader: Aluminum\r\n✔️ Warranty: Lifetime', 'ram.jpg', 69.95, 10, '2025-06-26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `created_at`, `name`) VALUES
(13, 'dorina.habravan@gmail.com', '$2y$10$XfsnMf1tWDHadtmrlDUnP.nOHqeTl4BfIUZ.DHCk6.iuC6eqHhLb.', '2025-07-03 21:07:43', 'Dorina Habravan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
