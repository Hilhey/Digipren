USE bd_muhammadhilman;

INSERT INTO categories (name, slug) VALUES
('Makanan','makanan'),
('Minuman','minuman'),
('Digital','digital')
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO products (category_id, name, price, stock, description, is_active)
SELECT c.id, 'Bakso Spesial', 25000, 50, 'Bakso enak, hangat, dan nikmat.', 1 FROM categories c WHERE c.slug='makanan';

INSERT INTO products (category_id, name, price, stock, description, is_active)
SELECT c.id, 'Es Teh Manis', 7000, 100, 'Segar untuk siang hari.', 1 FROM categories c WHERE c.slug='minuman';
