# Base Filament Project

Chào mừng bạn đến với **Base Filament**, một dự án mẫu sử dụng **Laravel Filament** để xây dựng giao diện quản trị mạnh mẽ và dễ tùy chỉnh. Hướng dẫn này sẽ giúp bạn cài đặt dự án và làm quen với các lệnh Filament phổ biến.

## 📋 Yêu cầu hệ thống
- **PHP**: >= 8.1
- **Composer**: Phiên bản mới nhất
- **Node.js & npm** (hoặc Yarn) để biên dịch tài nguyên frontend
- **Cơ sở dữ liệu**: MySQL, SQLite, hoặc tương thích
- **Web server**: Apache, Nginx, hoặc server tích hợp của Laravel
- **Git**: Để clone repository

## 🚀 Cài đặt dự án

### 1. Clone dự án
Clone repository từ GitHub và di chuyển vào thư mục dự án:
```bash
git clone https://github.com/dangphuong3110/base-filament.git
cd base-filament
```

### 2. Cài đặt dependencies
Cài đặt các gói PHP cần thiết thông qua Composer:
```bash
composer install
```

### 3. Cấu hình file môi trường
Sao chép file `.env.example` để tạo file `.env`:
```bash
cp .env.example .env
```
Mở file `.env` bằng trình soạn thảo (như VS Code) và cấu hình thông tin cơ sở dữ liệu:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Tạo khóa ứng dụng
Tạo khóa ứng dụng cho Laravel:
```bash
php artisan key:generate
```

### 5. Chạy migration
Tạo các bảng trong cơ sở dữ liệu:
```bash
php artisan migrate
```

### 6. Khởi động dự án
Chạy server tích hợp của Laravel:
```bash
php artisan serve
```
Truy cập giao diện quản trị tại:  
🔗 **[http://localhost:8000/admin](http://localhost:8000/admin)**  
Đăng ký tài khoản hoặc đăng nhập để sử dụng hệ thống.

---

## 🛠 Các lệnh Artisan phổ biến trong Filament

Dưới đây là danh sách các lệnh Artisan thường dùng khi làm việc với Filament, giúp bạn tạo và quản lý các thành phần như resource, page, widget, v.v.

| **Lệnh** | **Mô tả** |
|----------|-----------|
| **`php artisan make:filament-resource <NameResource>`** | Tạo resource để quản lý CRUD cho model. Tạo các file như:<br>- `NameResource.php`<br>- `NameResource/Pages/ListNameResources.php`<br>- `NameResource/Pages/CreateNameResource.php`<br>- `NameResource/Pages/EditNameResource.php`<br>**Tùy chọn**: `--generate` để tự động tạo form và table.<br>Ví dụ:<br>- ```php artisan make:filament-resource Post --generate```<br>- 📖 Xem thêm: [Filament Resources](https://filamentphp.com/docs/3.x/panels/resources/getting-started) |
| **`php artisan make:filament-page <NamePage>`** | Tạo trang tùy chỉnh (Livewire component) không liên quan trực tiếp đến resource. |
| **`php artisan make:filament-panel <NamePanel>`** | Tạo panel mới để hỗ trợ đa panel trong ứng dụng. |
| **`php artisan make:filament-relation-manager <NameResource> <NameRelationship> <NameColumn>`** | Tạo relation manager để quản lý các bản ghi liên quan trên trang chỉnh sửa.<br>Ví dụ:<br>```php artisan make:filament-relation-manager PatientResource treatments description``` |
| **`php artisan make:filament-widget <NameWidget> [--chart]`** | Tạo widget (thống kê hoặc biểu đồ) cho dashboard. Tùy chọn `--chart` tạo widget dạng biểu đồ (Chart.js).<br>Ví dụ:<br>```php artisan make:filament-widget TreatmentsChart --chart``` |
| **`php artisan filament:optimize`** | Cache các thành phần Filament và Blade icons để tối ưu hiệu suất. Nên chạy trong quá trình triển khai. |

---


### 7. Generate permissions/policies:
Generate permission cho các role:
```bash
php artisan shield:generate --all
```

### 8. Seed dữ liệu permission/role:
Seed dữ liệu permission/role:
```bash
php artisan db:seed --class=ShieldSeeder
```

### 9. Sử dụng tinker để assign role:
Sử dụng tinker để assign role cho người dùng:
```bash
php artisan tinker
> $user = User::find(1);
> $user->assignRole('super_admin');
```

## 🔧 Mẹo và lưu ý
- **Debug lỗi**: Kiểm tra log tại `storage/logs/laravel.log` nếu gặp sự cố. Chạy `composer dump-autoload` hoặc `php artisan cache:clear` để làm mới cấu hình.
- **Quyền thư mục**: Đảm bảo thư mục `storage` và `bootstrap/cache` có quyền ghi. Ví dụ:
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```
- **Cập nhật dependency**: Chạy `composer update` để đảm bảo các gói mới nhất.
- **Tùy chỉnh giao diện**: Các thay đổi về giao diện và logic CMS thường được thực hiện trong các file resource (`List*.php`, `Create*.php`, `Edit*.php`).

## 📚 Tài liệu tham khảo
- [Filament PHP Documentation](https://filamentphp.com/docs)
- [Laravel Documentation](https://laravel.com/docs)
- Repository: [dangphuong3110/base-filament](https://github.com/dangphuong3110/base-filament)

---

## 🙋 Hỗ trợ
Nếu bạn gặp vấn đề hoặc cần hỗ trợ thêm, hãy:
- Mở issue trên [GitHub](https://github.com/dangphuong3110/base-filament/issues).
- Liên hệ tác giả qua email hoặc các kênh được cung cấp trong repository.

Chúc bạn thành công với dự án! 🎉
