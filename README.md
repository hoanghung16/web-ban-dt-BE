# THE KING Store - Backend API (Laravel 10)

Đây là Backend API chạy bằng Laravel, cung cấp dữ liệu cho dự án THE KING Store (bán điện thoại điện tử).

## Tính năng chính
- Cung cấp API quản lý danh sách Users (`/api/users`), hỗ trợ full thao tác CRUD (Thêm, Xem, Sửa, Xóa).
- Cấu hình Seeder tự động nạp danh sách 5 Users (có fullname, email, password, role) khi deploy.
- Sử dụng `UserResource` để biến đổi trường `fullname` (trong CSDL) thành `name` ở phía API response cho Frontend sử dụng.
- Cấu hình cho phép CORS mở rộng để Frontend từ Render có thể gọi API.

## Hướng dẫn Deploy lên Render bằng Docker
Dự án có sẵn `Dockerfile`. Khi deploy lên Web Service của Render, chú ý:
1. Giao diện thiết lập Build Command: Để trống (Vì dùng Docker).
2. Khi bật container, `CMD` của Dockerfile đã được config chạy tự động hai lệnh sau:
   - `php artisan migrate --force`
   - `php artisan db:seed --force`
3. Điều này đảm bảo Database luôn được tạo mới và nạp sẵn 5 Users. Mật khẩu tất cả các User đều là `password`.

## Các Endpoint chính
### Users:
- `GET /api/users` : Lấy danh sách toàn bộ Users
- `POST /api/users` : Tạo một User mới (truyền field `name` thay vì `fullname`)
- `GET /api/users/{id}` : Lấy chi tiết một User
- `PUT /api/users/{id}` : Cập nhật thông tin User
- `DELETE /api/users/{id}` : Xóa một User
