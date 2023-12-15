SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_list
-- ----------------------------
DROP TABLE IF EXISTS `admin_list`;
CREATE TABLE `admin_list` (
  `adminId` int NOT NULL AUTO_INCREMENT,
  `adminName` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adminAccount` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adminPassword` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adminSession` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adminLastLoginTime` int DEFAULT NULL,
  `adminLastLoginIp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`adminId`) USING BTREE,
  UNIQUE KEY `adminAccount` (`adminAccount`) USING BTREE,
  KEY `adminSession` (`adminSession`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of admin_list
-- ----------------------------
BEGIN;
INSERT INTO `admin_list` VALUES (1, 'EasySwoole', 'easyswoole', 'e10adc3949ba59abbe56e057f20f883e', '', 1700891404, '127.0.0.1');
COMMIT;

-- ----------------------------
-- Table structure for banner_list
-- ----------------------------
DROP TABLE IF EXISTS `banner_list`;
CREATE TABLE `banner_list` (
  `bannerId` int NOT NULL AUTO_INCREMENT,
  `bannerName` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bannerImg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'banner图片',
  `bannerDescription` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bannerUrl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '跳转地址',
  `state` tinyint DEFAULT NULL COMMENT '状态0隐藏 1正常',
  PRIMARY KEY (`bannerId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of banner_list
-- ----------------------------
BEGIN;
INSERT INTO `banner_list` VALUES (1, '测试banner', 'asdadsasdasd.jpg', '测试的banner数据', 'www.easyswoole.com', 1);
COMMIT;

-- ----------------------------
-- Table structure for user_list
-- ----------------------------
DROP TABLE IF EXISTS `user_list`;
CREATE TABLE `user_list` (
  `userId` int NOT NULL AUTO_INCREMENT,
  `userName` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `userAccount` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `userPassword` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `addTime` int unsigned DEFAULT '0',
  `lastLoginIp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lastLoginTime` int unsigned DEFAULT '0',
  `userSession` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` tinyint unsigned DEFAULT '0',
  `money` int unsigned NOT NULL DEFAULT '0' COMMENT '用户余额',
  `frozenMoney` int unsigned NOT NULL DEFAULT '0' COMMENT '冻结余额',
  PRIMARY KEY (`userId`) USING BTREE,
  UNIQUE KEY `pk_userAccount` (`userAccount`) USING BTREE,
  KEY `userSession` (`userSession`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user_list
-- ----------------------------
BEGIN;
INSERT INTO `user_list` VALUES (1, 'easyswoole', 'easyswoole', 'e10adc3949ba59abbe56e057f20f883e', '18888888888', 0, '127.0.0.1', 1700892578, '', 0, 0, 0);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
