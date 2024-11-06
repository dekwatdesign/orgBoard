/*
 Navicat Premium Dump SQL

 Source Server         : MariaDB_localhost
 Source Server Type    : MariaDB
 Source Server Version : 110502 (11.5.2-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : image_management

 Target Server Type    : MariaDB
 Target Server Version : 110502 (11.5.2-MariaDB)
 File Encoding         : 65001

 Date: 06/11/2024 17:06:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for area
-- ----------------------------
DROP TABLE IF EXISTS `area`;
CREATE TABLE `area`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `background_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of area
-- ----------------------------

-- ----------------------------
-- Table structure for area_settings
-- ----------------------------
DROP TABLE IF EXISTS `area_settings`;
CREATE TABLE `area_settings`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  `setting_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of area_settings
-- ----------------------------
INSERT INTO `area_settings` VALUES (1, 'Screen Size', '2');
INSERT INTO `area_settings` VALUES (2, 'Background Image', 'area_20241106072302672b1956906651.46290508.jpg');

-- ----------------------------
-- Table structure for area_sizes
-- ----------------------------
DROP TABLE IF EXISTS `area_sizes`;
CREATE TABLE `area_sizes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `size_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  `size_width` double NULL DEFAULT NULL,
  `size_height` double NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of area_sizes
-- ----------------------------
INSERT INTO `area_sizes` VALUES (1, '1080p', 1920, 1080);
INSERT INTO `area_sizes` VALUES (2, '720p (Default)', 1280, 720);
INSERT INTO `area_sizes` VALUES (3, '480p', 720, 480);

-- ----------------------------
-- Table structure for global_sizes
-- ----------------------------
DROP TABLE IF EXISTS `global_sizes`;
CREATE TABLE `global_sizes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `size_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  `size_width` double NULL DEFAULT NULL,
  `size_height` double NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of global_sizes
-- ----------------------------
INSERT INTO `global_sizes` VALUES (1, '(64 x 64 px) ไอคอน', 64, 64);
INSERT INTO `global_sizes` VALUES (2, '(150 x 150 px) ภาพโปรไฟล์', 150, 150);
INSERT INTO `global_sizes` VALUES (3, '(250 x 100 px) โลโก้เว็บไซต์', 250, 100);
INSERT INTO `global_sizes` VALUES (4, '(512 x 512 px) ไอคอน (สำหรับแอปพลิเคชัน)', 512, 512);
INSERT INTO `global_sizes` VALUES (5, '(728 x 90 px) แบนเนอร์โฆษณา', 728, 90);
INSERT INTO `global_sizes` VALUES (6, '(800 x 800 px) ภาพสินค้า', 800, 800);
INSERT INTO `global_sizes` VALUES (7, '(820 x 312 px) ภาพปก Facebook', 820, 312);
INSERT INTO `global_sizes` VALUES (8, '(1,080 x 566 px) ภาพโพสต์โซเชียลมีเดีย - Instagram (Landscape)', 1080, 566);
INSERT INTO `global_sizes` VALUES (9, '(1,080 x 1,080 px) ภาพโพสต์โซเชียลมีเดีย - Instagram (Square)', 1080, 1080);
INSERT INTO `global_sizes` VALUES (10, '(1,200 x 630 px) ภาพโพสต์โซเชียลมีเดีย - Facebook', 1200, 630);
INSERT INTO `global_sizes` VALUES (11, '(1,200 x 675 px) ภาพโพสต์โซเชียลมีเดีย - Twitter', 1200, 675);
INSERT INTO `global_sizes` VALUES (12, '(1,200 x 800 px) ภาพ Responsive Design', 1200, 800);
INSERT INTO `global_sizes` VALUES (13, '(1,920 x 600 px) แบนเนอร์', 1920, 600);
INSERT INTO `global_sizes` VALUES (14, '(1,920 x 1,080 px) ภาพพื้นหลัง / ภาพหัวข้อ', 1920, 1080);

-- ----------------------------
-- Table structure for items
-- ----------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  `type_id` int(11) NULL DEFAULT NULL,
  `x_pos` int(11) NULL DEFAULT 0,
  `y_pos` int(11) NULL DEFAULT 0,
  `item_sizes_id` int(11) NULL DEFAULT 100,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `item_sizes_id`(`item_sizes_id` ASC) USING BTREE,
  CONSTRAINT `items_ibfk_1` FOREIGN KEY (`item_sizes_id`) REFERENCES `items_sizes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 60 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for items_components
-- ----------------------------
DROP TABLE IF EXISTS `items_components`;
CREATE TABLE `items_components`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NULL DEFAULT NULL,
  `item_setting_id` int(11) NULL DEFAULT NULL,
  `item_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `item_id`(`item_id` ASC) USING BTREE,
  INDEX `item_setting_id`(`item_setting_id` ASC) USING BTREE,
  CONSTRAINT `items_components_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `items_components_ibfk_2` FOREIGN KEY (`item_setting_id`) REFERENCES `items_settings` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 310 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for items_frame_size
-- ----------------------------
DROP TABLE IF EXISTS `items_frame_size`;
CREATE TABLE `items_frame_size`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `size_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  `size_px` double NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of items_frame_size
-- ----------------------------
INSERT INTO `items_frame_size` VALUES (1, 'Larges', 22);
INSERT INTO `items_frame_size` VALUES (2, 'Medium', 15);
INSERT INTO `items_frame_size` VALUES (3, 'Thin', 10);

-- ----------------------------
-- Table structure for items_settings
-- ----------------------------
DROP TABLE IF EXISTS `items_settings`;
CREATE TABLE `items_settings`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  `setting_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  `setting_sort` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  `setting_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of items_settings
-- ----------------------------
INSERT INTO `items_settings` VALUES (1, 'คำนำหน้า', 'selection', '1', 'item_pname');
INSERT INTO `items_settings` VALUES (2, 'ชื่อ', 'input', '2', 'item_fname');
INSERT INTO `items_settings` VALUES (3, 'นามสกุล', 'input', '3', 'item_lname');
INSERT INTO `items_settings` VALUES (4, 'ตำแหน่ง/ศูนย์', 'input', '4', 'item_work_position');
INSERT INTO `items_settings` VALUES (5, 'ภาพประจำตัว', 'image', '5', 'item_avatar');
INSERT INTO `items_settings` VALUES (6, 'กรอบภาพ', 'image', '6', 'item_frame');
INSERT INTO `items_settings` VALUES (7, 'ขนาดกรอบ', 'selection', '7', 'item_frame_size');
INSERT INTO `items_settings` VALUES (8, 'พื้นหลัง', 'image', '8', 'item_bg');

-- ----------------------------
-- Table structure for items_sizes
-- ----------------------------
DROP TABLE IF EXISTS `items_sizes`;
CREATE TABLE `items_sizes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `size_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  `size_width` double NULL DEFAULT NULL,
  `size_height` double NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of items_sizes
-- ----------------------------
INSERT INTO `items_sizes` VALUES (1, 'Max ( 500 x 500 )', 500, 500);
INSERT INTO `items_sizes` VALUES (2, 'Large (400 x 400)', 400, 400);
INSERT INTO `items_sizes` VALUES (3, 'Medium (300 x 300)', 300, 300);
INSERT INTO `items_sizes` VALUES (4, 'Small (150 x 150)', 150, 150);
INSERT INTO `items_sizes` VALUES (5, 'Smallest (100 x 100)', 100, 100);

-- ----------------------------
-- Table structure for items_types
-- ----------------------------
DROP TABLE IF EXISTS `items_types`;
CREATE TABLE `items_types`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of items_types
-- ----------------------------
INSERT INTO `items_types` VALUES (1, 'การ์ดบุคคล');
INSERT INTO `items_types` VALUES (2, 'รูปภาพ');
INSERT INTO `items_types` VALUES (3, 'ตัวหนังสือ');

-- ----------------------------
-- Table structure for prefix_name
-- ----------------------------
DROP TABLE IF EXISTS `prefix_name`;
CREATE TABLE `prefix_name`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `prefix_title_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of prefix_name
-- ----------------------------
INSERT INTO `prefix_name` VALUES (1, 'นาย', 'Mr.');
INSERT INTO `prefix_name` VALUES (2, 'นาง', 'Mrs.');
INSERT INTO `prefix_name` VALUES (3, 'นางสาว', 'Miss');
INSERT INTO `prefix_name` VALUES (4, 'สามเณร', 'Novice monk');
INSERT INTO `prefix_name` VALUES (5, 'พระ', 'Monk');
INSERT INTO `prefix_name` VALUES (6, 'พระอธิการ', 'Abbot');
INSERT INTO `prefix_name` VALUES (7, 'พระปลัด', 'Sub-abbot');
INSERT INTO `prefix_name` VALUES (8, 'พระใบฎีกา', 'Preacher');
INSERT INTO `prefix_name` VALUES (9, 'พระครูสมุห์', 'Dean');
INSERT INTO `prefix_name` VALUES (10, 'พระมหา', 'Venerable');
INSERT INTO `prefix_name` VALUES (11, 'พระครูวินัยธร', 'Disciplinarian');
