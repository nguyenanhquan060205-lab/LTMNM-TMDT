# Legacy Schema Mapping

Legacy ASP.NET MVC và SQL dump cũ chỉ dùng tham chiếu nghiệp vụ.

| Legacy | Canonical |
| --- | --- |
| `NGUOIDUNG`, `nguoi_dungs` | `users` |
| `LOAISANPHAM`, `loai_san_phams` | `categories` |
| `SANPHAM`, `san_phams` | `products` |
| `HINHANHSP`, `hinh_anh_s_p_s` | `product_images` |
| `GIOHANG`, `gio_hangs` | `carts` |
| `CT_GIOHANG`, `ct_gio_hangs` | `cart_items` |
| `HOADON`, `hoa_dons` | `orders` |
| `CT_HOADON`, `ct_hoa_dons` | `order_items` |
| `DANHGIA`, `danh_gias` | `reviews` |
| `KHIEUNAI`, `khieu_nais` | `complaints` |
| `TINNHAN`, `tin_nhans` | `messages` |

Legacy fields with data migration risk:

- `MatKhau` must migrate to hashed `password`.
- Vietnamese status strings must map to enum values.
- Composite keys in cart/order details must migrate to `id`.
- Image paths under `Content` must migrate to Laravel Storage paths.
