# Venshop - Online Sale (Laravel E-Commerce)

## Mô tả
Đây là dự án website thương mại điện tử xây dựng bằng **Laravel** với đầy đủ chức năng quản lý sản phẩm, đơn hàng, khách hàng, quản trị viên, đa ngôn ngữ, thống kê trực quan, ...

---

## Yêu cầu hệ thống
- PHP >= 8.0
- Composer
- MySQL/MariaDB
- Node.js & npm (nếu muốn build lại frontend assets)
- Extension: fileinfo, mbstring, openssl, pdo, tokenizer, xml, ctype, json, bcmath, gd

---

## Hướng dẫn cài đặt & chạy dự án

### 1. Clone source code
```bash
git clone <repo-url> venshop
cd venshop
```

### 2. Cài đặt package PHP
```bash
composer install
```

### 3. Cài đặt package JS (nếu cần build lại frontend)
```bash
npm install
# npm run dev (nếu muốn build lại css/js)
```

### 4. Tạo file cấu hình môi trường
```bash
cp .env.example .env
```

- Chỉnh sửa file `.env` cho đúng thông tin database:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=venshop
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

### 5. Tạo key ứng dụng
```bash
php artisan key:generate
```

### 6. Migrate & seed database
```bash
php artisan migrate --seed
```
- Nếu muốn seed lại dữ liệu mẫu:
```bash
php artisan db:seed
```

### 7. Phân quyền thư mục
- Đảm bảo các thư mục sau có quyền ghi cho web server:
```
storage/
bootstrap/cache/
public/storage/
```

### 8. Tạo symlink cho storage (để truy cập ảnh upload)
```bash
php artisan storage:link
```

### 9. Chạy server local
```bash
php artisan serve
```
- Truy cập: http://localhost:8000

---

## Tài khoản mẫu
- **Admin:**
  - Email: `admin@example.com`
  - Password: `password`
- **User:**
  - Email: `user@example.com`
  - Password: `password`

> Bạn có thể đăng ký tài khoản mới hoặc dùng các tài khoản mẫu trên.

---

## Một số lưu ý
- Để upload logo, banner, ảnh sản phẩm... hãy vào trang quản trị (admin) và sử dụng chức năng tương ứng.
- Để đổi ngôn ngữ, dùng menu góc phải trên cùng.
- Nếu gặp lỗi về quyền file, hãy cấp quyền ghi cho các thư mục storage, bootstrap/cache, public/storage.
- Nếu seed sản phẩm không ra ảnh, kiểm tra lại quyền ghi thư mục và symlink storage.
- Để build lại frontend assets: `npm run dev` hoặc `npm run build`.

---

## Production
- Sử dụng web server (Nginx/Apache) trỏ vào thư mục `public/`.
- Thiết lập biến môi trường `.env` phù hợp.
- Chạy lệnh migrate, seed, storage:link như trên.
- Đảm bảo quyền ghi cho storage, cache.
- Có thể bật cache config/view để tăng tốc:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Đóng góp & phát triển
- Fork, tạo branch mới, pull request.
- Mọi ý kiến đóng góp, vui lòng liên hệ admin dự án.

---

Chúc bạn cài đặt và sử dụng hệ thống thành công!
