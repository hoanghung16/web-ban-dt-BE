# Hướng Dẫn Deploy trên Render

## 📱 Local Development (UPLOAD_DRIVER=local)

### File .env:
```
UPLOAD_DRIVER=local
```

**Hình ảnh lưu tại:** `public/images/products/`

---

## 🌐 Production on Render (UPLOAD_DRIVER=cloudinary)

### Bước 1: Tạo/Lấy Cloudinary Credentials
1. Đăng ký: https://cloudinary.com/users/register/free
2. Vào Dashboard lấy:
   - `CLOUDINARY_CLOUD_NAME`
   - `CLOUDINARY_API_KEY`
   - `CLOUDINARY_API_SECRET`

### Bước 2: Set Environment Variables trên Render

Truy cập **Render Dashboard** → **Backend Service** → **Environment**

Thêm các biến:

```
UPLOAD_DRIVER=cloudinary
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
```

### Bước 3: Deploy
```bash
git push origin main
```

Render sẽ tự động:
- Pull code mới
- Chạy migrations
- Upload hình ảnh sẽ lưu trên Cloudinary

---

## ✅ So Sánh

| Môi trường | UPLOAD_DRIVER | Lưu tại | Persistent |
|-----------|---------------|---------|-----------|
| **Local** | `local` | `/public/images/products/` | ✅ Yes |
| **Render** | `cloudinary` | Cloudinary Cloud | ✅ Yes (CDN) |

---

## 🚨 Lưu Ý

- **Local:** Ảnh lưu trực tiếp trên disk
- **Render:** Nếu không set `UPLOAD_DRIVER=cloudinary` → hình sẽ MẤT sau khi restart
- **Giải pháp:** Luôn dùng Cloudinary cho production

---

## 🔧 Troubleshooting

### Lỗi: "Lỗi upload: ..."
- Check Cloudinary credentials
- Kiểm tra `UPLOAD_DRIVER` env variable

### Hình không hiện trên Render
- Verify `UPLOAD_DRIVER=cloudinary`
- Check Cloudinary account còn quota không
