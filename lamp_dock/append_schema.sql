-- 購入履歴テーブル(history)
-- 注文番号(order_id、主キー)、ユーザーID(user_id)、購入日時(order_dated)
CREATE TABLE `history` (
  `order_id`    int(11) AUTO_INCREMENT NOT NULL,
  `user_id`     int(11) NOT NULL,
  `order_dated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  primary key(order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 購入明細テーブル(detail)
-- 明細番号(detail_id、主キー)、注文番号(order_id)、アイテムID(item_id)、購入数(amount)、購入した時の価格(order_price)
CREATE TABLE `detail` (
  `detail_id`   int(11) AUTO_INCREMENT NOT NULL,
  `order_id`    int(11) NOT NULL,
  `item_id`     int(11) NOT NULL,
  `amount`      int(11) NOT NULL,
  `order_price` int(11) NOT NULL,
  primary key(detail_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;