using Antlr.Runtime;
using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.IO;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using ThuongMaiDienTu_DoAn.Filters; // Giữ nguyên nếu bạn có dùng Filter
using ThuongMaiDienTu_DoAn.Models;

namespace ThuongMaiDienTu_DoAn.Controllers
{
    public class SanPhamController : Controller
    {
        private readonly TMDTEntities db = new TMDTEntities();

        // 1. TRANG CHỦ / DANH SÁCH
        //public ActionResult Index(string q, int? maloai)
        //{
        //    var u = Session["user"] as NGUOIDUNG;
        //    // CHỈ HIỆN TIN ĐÃ DUYỆT (Ẩn/Khóa thì không hiện)
        //    var sp = db.SANPHAMs.Where(s => s.TrangThai == "Đã duyệt");

        //    if (u != null)
        //        sp = sp.Where(s => s.MaKH != u.MaKH);

        //    if (!string.IsNullOrWhiteSpace(q))
        //        sp = sp.Where(s => s.TenSP.Contains(q));

        //    if (maloai.HasValue)
        //        sp = sp.Where(s => s.MaLoai == maloai.Value);

        //    ViewBag.LoaiSP = db.LOAISANPHAMs.ToList();
        //    ViewBag.TuKhoa = q;
        //    ViewBag.LoaiDangChon = maloai;

        //    return View(sp.OrderByDescending(x => x.NgayTao).ToList());
        //}

        public ActionResult Index(string q, int? maloai, int page = 1)
        {
            int pageSize = 12;

            var u = Session["user"] as NGUOIDUNG;

            var sp = db.SANPHAMs.Where(s => s.TrangThai == "Đã duyệt");

            if (u != null)
                sp = sp.Where(s => s.MaKH != u.MaKH);

            if (!string.IsNullOrWhiteSpace(q))
                sp = sp.Where(s => s.TenSP.Contains(q));

            if (maloai.HasValue)
                sp = sp.Where(s => s.MaLoai == maloai.Value);

            sp = sp.OrderByDescending(x => x.NgayTao);

            // ======= PHÂN TRANG =======
            int totalItems = sp.Count();
            int totalPages = (int)Math.Ceiling((double)totalItems / pageSize);

            ViewBag.Page = page;
            ViewBag.TotalPages = totalPages;
            ViewBag.q = q;
            ViewBag.maloai = maloai;

            var items = sp.Skip((page - 1) * pageSize)
                          .Take(pageSize)
                          .ToList();

            // ==========================

            ViewBag.LoaiSP = db.LOAISANPHAMs.ToList();

            return View(items);
        }


        // 2. CHI TIẾT SẢN PHẨM
        //public ActionResult ChiTiet(int id)
        //{
        //    // ============================
        //    // 1. LẤY SẢN PHẨM
        //    // ============================
        //    var sp = db.SANPHAMs.Find(id);

        //    if (sp == null || sp.TrangThai == "Ẩn")
        //    {
        //        var u = Session["user"] as NGUOIDUNG;

        //        // Nếu user không phải chủ sản phẩm → 404
        //        if (u == null || sp == null || sp.MaKH != u.MaKH)
        //            return HttpNotFound();
        //    }

        //    // ============================
        //    // 2. LẤY ĐÁNH GIÁ SẢN PHẨM
        //    // ============================
        //    var danhGia = db.DANHGIAs
        //                    .Where(d => d.MaSP == id)
        //                    .OrderByDescending(d => d.NgayDG)
        //                    .ToList();

        //    ViewBag.TongDanhGia = danhGia.Count();
        //    ViewBag.TrungBinhDanhGia = danhGia.Any()
        //        ? Math.Round(danhGia.Average(d => d.SoSao), 1)
        //        : 0;

        //    // Gửi danh sách đánh giá để hiển thị chi tiết từng người đánh giá
        //    ViewBag.ListDanhGia = danhGia;

        //    // ============================
        //    // 3. ẢNH CHI TIẾT
        //    // ============================
        //    ViewBag.AnhChiTiet = db.HINHANHSPs
        //        .Where(a => a.Masp == id && a.AnhBia == false)
        //        .ToList();

        //    // ============================
        //    // 4. SẢN PHẨM LIÊN QUAN (+ ĐIỂM)
        //    // ============================
        //    var spLienQuan = db.SANPHAMs
        //        .Where(x => x.MaLoai == sp.MaLoai
        //                 && x.MaSP != sp.MaSP
        //                 && x.TrangThai == "Đã duyệt")
        //        .OrderByDescending(x => x.NgayTao)
        //        .Take(4)
        //        .ToList();

        //    // Gộp sản phẩm + đánh giá vào 1 object
        //    var listLienQuan = spLienQuan.Select(item => new
        //    {
        //        SP = item,
        //        TongDG = db.DANHGIAs.Count(d => d.MaSP == item.MaSP),
        //        DiemTB = db.DANHGIAs.Where(d => d.MaSP == item.MaSP).Any()
        //                 ? Math.Round(db.DANHGIAs.Where(d => d.MaSP == item.MaSP).Average(d => d.SoSao), 1)
        //                 : 0
        //    }).ToList();

        //    ViewBag.SPLienQuan = listLienQuan;

        //    // ============================
        //    // 5. QUYỀN SỞ HỮU
        //    // ============================
        //    var currentUser = Session["user"] as NGUOIDUNG;
        //    if (currentUser != null && currentUser.MaKH == sp.MaKH)
        //        return View("ChiTietCuaNguoiBan", sp);

        //    // ============================
        //    // 6. TRẢ VỀ VIEW CHÍNH
        //    // ============================
        //    return View(sp);
        //}

        public ActionResult ChiTiet(int id, int pageDG = 1, int pageSP = 1)
        {
            var sp = db.SANPHAMs
                        .Include("NGUOIDUNG")
                        .Include("HINHANHSPs")
                        .FirstOrDefault(x => x.MaSP == id);

            if (sp == null)
                return HttpNotFound();

            var u = Session["user"] as NGUOIDUNG;

            if (sp.TrangThai == "Ẩn")
            {
                if (u == null || sp.MaKH != u.MaKH)
                    return HttpNotFound();
            }

            int pageSizeDG = 5;
            var danhGia = db.DANHGIAs
                            .Where(d => d.MaSP == id)
                            .OrderByDescending(d => d.NgayDG)
                            .ToList();

            ViewBag.TongDanhGia = danhGia.Count();
            ViewBag.TrungBinhDanhGia = danhGia.Any()
                ? Math.Round(danhGia.Average(d => d.SoSao), 1)
                : 0;

            int totalPageDG = (int)Math.Ceiling((double)danhGia.Count / pageSizeDG);
            ViewBag.ListDanhGia = danhGia.Skip((pageDG - 1) * pageSizeDG).Take(pageSizeDG).ToList();
            ViewBag.PageDG = pageDG;
            ViewBag.TotalPageDG = totalPageDG;

            ViewBag.AnhChiTiet = sp.HINHANHSPs
                                    .Where(a => a.AnhBia == false)
                                    .ToList();

            int pageSizeSP = 4;
            var spLienQuan = db.SANPHAMs
                                .Include("HINHANHSPs")
                                .Include("NGUOIDUNG")
                                .Where(x => x.MaLoai == sp.MaLoai &&
                                            x.MaSP != sp.MaSP &&
                                            x.TrangThai == "Đã duyệt")
                                .OrderByDescending(x => x.NgayTao)
                                .ToList();

            int totalPageSP = (int)Math.Ceiling((double)spLienQuan.Count / pageSizeSP);
            ViewBag.SPLienQuan = spLienQuan.Skip((pageSP - 1) * pageSizeSP).Take(pageSizeSP).ToList();
            ViewBag.PageSP = pageSP;
            ViewBag.TotalPageSP = totalPageSP;

            if (u != null && u.MaKH == sp.MaKH)
                return View("ChiTietCuaNguoiBan", sp);

            return View(sp);
        }

        public ActionResult ThongTinNguoiBan(int idNguoiBan)
        {
            var nguoiBan = db.NGUOIDUNGs.Find(idNguoiBan);
            if (nguoiBan == null)
                return HttpNotFound();

            var sanPhamCuaNguoiBan = db.SANPHAMs
                .Where(sp => sp.MaKH == idNguoiBan && sp.TrangThai == "Đã duyệt")
                .ToList();

            ViewBag.SanPham = sanPhamCuaNguoiBan;
            return View(nguoiBan);
        }


        // 3. TẠO MỚI (GET)
        [HttpGet]
        public ActionResult TaoMoi()
        {
            if (Session["user"] == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            ViewBag.MaLoai = new SelectList(db.LOAISANPHAMs, "MaLoai", "TenLoai");
            return View();
        }

        // 3. TẠO MỚI (POST) - SỬA LOGIC TRẠNG THÁI TẠI ĐÂY
        [HttpPost]
        [ValidateInput(false)] // Cho phép nhập HTML nếu cần
        public ActionResult TaoMoi(SANPHAM m, IEnumerable<HttpPostedFileBase> files)
        {
            var u = Session["user"] as NGUOIDUNG;
            if (u == null) return RedirectToAction("DangNhap", "TaiKhoan");

            // GÁN THÔNG TIN
            m.MaKH = u.MaKH;
            m.NgayTao = DateTime.Now;

            // [THAY ĐỔI]: Mặc định là "Đã duyệt" luôn (Bỏ qua bước chờ duyệt)
            m.TrangThai = "Đã duyệt";

            db.SANPHAMs.Add(m);
            db.SaveChanges(); // Lưu để lấy MaSP

            // XỬ LÝ ẢNH (Giữ nguyên logic cũ của bạn)
            if (files != null && files.Any(f => f != null && f.ContentLength > 0))
            {
                bool firstImage = true;
                foreach (var file in files)
                {
                    if (file == null || file.ContentLength == 0) continue;

                    string ext = Path.GetExtension(file.FileName).ToLower();
                    string[] allow = { ".jpg", ".jpeg", ".png", ".gif", ".webp" };
                    if (!allow.Contains(ext)) continue;

                    string fileName = Guid.NewGuid().ToString() + ext;
                    string savePath = Path.Combine(Server.MapPath("~/Content/Images/"), fileName);
                    file.SaveAs(savePath);

                    db.HINHANHSPs.Add(new HINHANHSP
                    {
                        Masp = m.MaSP,
                        URLAnh = fileName,
                        AnhBia = firstImage
                    });
                    firstImage = false;
                }
                db.SaveChanges();
            }
            else
            {
                // Ảnh mặc định
                db.HINHANHSPs.Add(new HINHANHSP { Masp = m.MaSP, URLAnh = "noimage.jpg", AnhBia = true });
                db.SaveChanges();
            }

            TempData["OK"] = "🎉 Đăng tin thành công! Sản phẩm đã được hiển thị.";
            return RedirectToAction("CuaToi");
        }

        // 4. TIN CỦA TÔI
        public ActionResult CuaToi()
        {
            var u = Session["user"] as NGUOIDUNG;
            if (u == null) return RedirectToAction("DangNhap", "TaiKhoan");

            var list = db.SANPHAMs
                .Where(x => x.MaKH == u.MaKH)
                .OrderByDescending(x => x.NgayTao)
                .ToList();

            return View(list);
        }

        // 5. SỬA TIN (GET)
        [HttpGet]
        public ActionResult Sua(int id)
        {
            var sanPham = db.SANPHAMs.Find(id);
            // Kiểm tra quyền sở hữu (bổ sung cho an toàn)
            var u = Session["user"] as NGUOIDUNG;
            if (u == null || sanPham == null || sanPham.MaKH != u.MaKH)
            {
                return RedirectToAction("Index");
            }

            ViewBag.MaLoai = new SelectList(db.LOAISANPHAMs, "MaLoai", "TenLoai", sanPham.MaLoai);
            return View(sanPham);
        }

        // 5. SỬA TIN (POST) - ĐÃ HOÀN THIỆN LOGIC XỬ LÝ ẢNH BÌA VÀ ẢNH CHI TIẾT RIÊNG BIỆT
        [HttpPost]
        [ValidateAntiForgeryToken]
        [ValidateInput(false)] // Cho phép nhập HTML cho Mô tả
        public ActionResult Sua(SANPHAM model, HttpPostedFileBase anhBiaMoi, IEnumerable<HttpPostedFileBase> files, int? id)
        {
            if (!id.HasValue) return HttpNotFound();
            var sp = db.SANPHAMs.Find(id.Value);

            if (sp == null) return HttpNotFound();

            // Kiểm tra quyền sở hữu
            var u = Session["user"] as NGUOIDUNG;
            if (u == null || sp.MaKH != u.MaKH)
            {
                TempData["Loi"] = "Bạn không có quyền sửa sản phẩm này.";
                return RedirectToAction("Index");
            }

            // ===== Cập nhật thông tin cơ bản =====
            sp.TenSP = model.TenSP;
            sp.Gia = model.Gia;
            sp.MoTa = model.MoTa;
            sp.SoLuong = model.SoLuong;
            sp.MaLoai = model.MaLoai;

            if (sp.TrangThai != "Ẩn")
                sp.TrangThai = "Đã duyệt";

            // Lấy đường dẫn lưu ảnh
            string imgPath = Server.MapPath("~/Content/Images/");
            string[] allow = { ".jpg", ".jpeg", ".png", ".gif", ".webp" }; // Danh sách đuôi file hợp lệ

            // ===================================
            // 1. XỬ LÝ ẢNH BÌA (Chỉ thay đổi nếu có file mới)
            // ===================================
            if (anhBiaMoi != null && anhBiaMoi.ContentLength > 0)
            {
                string extBia = Path.GetExtension(anhBiaMoi.FileName)?.ToLower();

                if (allow.Contains(extBia))
                {
                    // A. Xóa ảnh bìa cũ trên Server và DB
                    var anhBiaCu = db.HINHANHSPs.FirstOrDefault(a => a.Masp == sp.MaSP && a.AnhBia == true);
                    if (anhBiaCu != null && anhBiaCu.URLAnh != "noimage.jpg")
                    {
                        string fullPathCu = Path.Combine(imgPath, anhBiaCu.URLAnh);
                        if (System.IO.File.Exists(fullPathCu))
                        {
                            System.IO.File.Delete(fullPathCu);
                        }
                        db.HINHANHSPs.Remove(anhBiaCu);
                    }

                    // B. Lưu ảnh bìa mới
                    string fileName = Guid.NewGuid().ToString() + extBia;
                    string savePath = Path.Combine(imgPath, fileName);
                    anhBiaMoi.SaveAs(savePath);

                    // C. Thêm bản ghi ảnh bìa mới vào DB
                    db.HINHANHSPs.Add(new HINHANHSP
                    {
                        Masp = sp.MaSP,
                        URLAnh = fileName,
                        AnhBia = true // Ảnh bìa
                    });
                }
                // else: Nếu file ảnh bìa không hợp lệ, bỏ qua, giữ nguyên ảnh bìa cũ.
            }

            // ===================================
            // 2. XỬ LÝ ẢNH CHI TIẾT (Nếu có file mới -> THAY THẾ TOÀN BỘ ảnh chi tiết cũ)
            // ===================================
            var validFiles = files?.Where(f => f != null && f.ContentLength > 0).ToList();

            if (validFiles != null && validFiles.Any())
            {
                // A. Xóa tất cả ảnh chi tiết cũ trên Server và DB
                var anhChiTietCu = db.HINHANHSPs.Where(a => a.Masp == sp.MaSP && a.AnhBia == false).ToList();
                foreach (var anhCu in anhChiTietCu)
                {
                    string fullPathCu = Path.Combine(imgPath, anhCu.URLAnh);
                    if (System.IO.File.Exists(fullPathCu))
                    {
                        System.IO.File.Delete(fullPathCu);
                    }
                }
                db.HINHANHSPs.RemoveRange(anhChiTietCu);

                // B. Lưu và thêm ảnh chi tiết mới vào DB
                foreach (var file in validFiles)
                {
                    string ext = Path.GetExtension(file.FileName).ToLower();
                    if (allow.Contains(ext))
                    {
                        string fileName = Guid.NewGuid().ToString() + ext;
                        string savePath = Path.Combine(imgPath, fileName);
                        file.SaveAs(savePath);

                        db.HINHANHSPs.Add(new HINHANHSP
                        {
                            Masp = sp.MaSP,
                            URLAnh = fileName,
                            AnhBia = false // Ảnh chi tiết
                        });
                    }
                }
            }

            // ===== Lưu thay đổi cuối cùng =====
            db.Entry(sp).State = EntityState.Modified;
            db.SaveChanges();

            TempData["OK"] = "✔ Cập nhật sản phẩm thành công!";
            return RedirectToAction("ChiTiet", new { id = sp.MaSP });
        }


        // 6. XÓA TIN
        [HttpGet]
        public ActionResult Xoa(int id)
        {
            var u = Session["user"] as NGUOIDUNG;
            var sanPham = db.SANPHAMs.Find(id);

            if (u == null || sanPham == null || sanPham.MaKH != u.MaKH)
            {
                TempData["Loi"] = "Bạn không có quyền xóa sản phẩm này.";
                return RedirectToAction("Index", "Home");
            }

            try
            {
                // Xóa ảnh server
                var hinhAnh = db.HINHANHSPs.Where(a => a.Masp == id).ToList();
                string path = Server.MapPath("~/Content/Images/");
                foreach (var anh in hinhAnh)
                {
                    string fullPath = Path.Combine(path, anh.URLAnh);
                    if (System.IO.File.Exists(fullPath) && anh.URLAnh != "noimage.jpg")
                    {
                        System.IO.File.Delete(fullPath);
                    }
                }

                // Xóa ảnh DB
                db.HINHANHSPs.RemoveRange(hinhAnh);

                // Xóa sản phẩm
                db.SANPHAMs.Remove(sanPham);
                db.SaveChanges();

                TempData["OK"] = "🗑️ Sản phẩm đã được xóa thành công.";
            }
            catch (Exception)
            {
                // Nếu dính khóa ngoại (đã có đơn hàng) -> Chuyển sang trạng thái Ẩn thay vì Xóa
                // Đây là giải pháp an toàn (Soft Delete)
                sanPham.TrangThai = "Ẩn"; // Hoặc "Đã xóa"
                db.SaveChanges();
                TempData["OK"] = "Sản phẩm đã được ẩn (do đã có lịch sử giao dịch).";
            }

            return RedirectToAction("CuaToi");
        }

        // 7. CÁC HÀM KHÁC (SanPhamDaBan, HoanThanh...) - GIỮ NGUYÊN
        //public ActionResult SanPhamDaBan()
        //{
        //    var u = Session["user"] as NGUOIDUNG;
        //    if (u == null) return RedirectToAction("DangNhap", "TaiKhoan");

        //    var sanPhamDaBan = db.CT_HOADON
        //        .Where(ct => ct.SANPHAM.MaKH == u.MaKH && ct.HOADON.MaKH != u.MaKH)
        //        .Select(ct => new SanPhamDaBanViewModel
        //        {
        //            MaHD = ct.MaHD,
        //            MaSP = ct.MaSP,
        //            TenSP = ct.SANPHAM.TenSP,
        //            GiaBan = (decimal)ct.SANPHAM.Gia,
        //            SoLuongBan = (int)ct.SoLuong,
        //            ThanhTien = ct.ThanhTien,
        //            NguoiMua = ct.HOADON.NGUOIDUNG.HoTen ?? "Không rõ",
        //            NgayMua = ct.HOADON.NgayDat ?? DateTime.Now,
        //            TrangThai = ct.TrangThaiCT

        //        })
        //        .OrderByDescending(x => x.NgayMua)
        //        .ToList();

        //    return View(sanPhamDaBan);
        //}

        public ActionResult SanPhamDaBan()
        {
            var u = Session["user"] as NGUOIDUNG;
            if (u == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            // Lấy các hóa đơn có sản phẩm do user bán
            var dsHoaDonBan = db.HOADONs
                .Where(hd =>
                    hd.CT_HOADON.Any(ct => ct.SANPHAM.MaKH == u.MaKH)
                )
                .OrderByDescending(hd => hd.NgayDat)
                .Select(hd => new HoaDonDaBanViewModel
                {
                    MaHD = hd.MaHD,
                    NgayDat = hd.NgayDat,
                    NgayTT = hd.NgayTT,
                    NguoiMua = hd.NGUOIDUNG.HoTen,
                    TongTien = hd.CT_HOADON
                        .Where(ct => ct.SANPHAM.MaKH == u.MaKH)
                        .Sum(ct => ct.ThanhTien),
                    TrangThai = hd.TrangThai
                })
                .ToList();

            return View(dsHoaDonBan);
        }

        public ActionResult CT_SanPhamDaBan(int id)
        {
            var u = Session["user"] as NGUOIDUNG;
            if (u == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            var hd = db.HOADONs.FirstOrDefault(h => h.MaHD == id);
            if (hd == null)
                return HttpNotFound();

            // Chỉ cho phép xem nếu hóa đơn có SP của user
            if (!hd.CT_HOADON.Any(ct => ct.SANPHAM.MaKH == u.MaKH) && u.VaiTro != "Admin")
                return new HttpStatusCodeResult(403);

            var chiTiet = db.CT_HOADON
                .Where(ct =>
                    ct.MaHD == id &&
                    ct.SANPHAM.MaKH == u.MaKH
                )
                .Select(ct => new ChiTietHoaDonViewModel
                {
                    MaHD = ct.MaHD,
                    MaSP = ct.MaSP,
                    TenSP = ct.SANPHAM.TenSP,
                    SoLuong = ct.SoLuong,
                    ThanhTien = ct.ThanhTien,
                    TrangThaiCT = ct.TrangThaiCT,
                    DaDanhGia = db.DANHGIAs.Any(d =>
                        d.MaHD == ct.MaHD &&
                        d.MaSP == ct.MaSP
                    )
                })
                .ToList();

            ViewBag.HoaDon = hd;

            return View(chiTiet);
        }

        [HttpPost]
        public ActionResult HoanThanhHoaDon(int id)
        {
            var u = Session["user"] as NGUOIDUNG;
            if (u == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            using (var tran = db.Database.BeginTransaction())
            {
                try
                {
                    var hd = db.HOADONs
                               .Include("CT_HOADON.SANPHAM")
                               .FirstOrDefault(h => h.MaHD == id);

                    if (hd == null)
                        return HttpNotFound();

                    var dsCT = hd.CT_HOADON
                                 .Where(ct =>
                                     ct.SANPHAM.MaKH == u.MaKH &&
                                     ct.TrangThaiCT == "Chờ xác nhận"
                                 )
                                 .ToList();

                    if (!dsCT.Any())
                    {
                        TempData["ThongBao"] = "Không có sản phẩm nào cần xác nhận.";
                        return RedirectToAction("CT_SanPhamDaBan", new { id });
                    }

                    foreach (var ct in dsCT)
                    {
                        ct.TrangThaiCT = "Đã xác nhận";
                    }

                    db.SaveChanges();

                    bool allDone = hd.CT_HOADON.All(ct => ct.TrangThaiCT == "Đã xác nhận");

                    if (allDone)
                    {
                        hd.TrangThai = "Đã thanh toán";
                        hd.NgayTT = DateTime.Now;
                        db.SaveChanges();
                    }

                    tran.Commit();

                    return RedirectToAction("SanPhamDaBan");
                }
                catch (Exception)
                {
                    tran.Rollback();
                    TempData["Error"] = "Có lỗi xảy ra khi hoàn thành hóa đơn.";
                    return RedirectToAction("CT_SanPhamDaBan", new { id });
                }
            }
        }

        [HttpPost]
        public ActionResult HuyHoaDonBan(int id)
        {
            var u = Session["user"] as NGUOIDUNG;
            if (u == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            using (var tran = db.Database.BeginTransaction())
            {
                try
                {
                    var hd = db.HOADONs
                               .Include("CT_HOADON.SANPHAM")
                               .FirstOrDefault(h => h.MaHD == id);

                    if (hd == null)
                        return HttpNotFound();

                    var dsCT = hd.CT_HOADON
                                 .Where(ct =>
                                     ct.TrangThaiCT == "Chờ xác nhận" &&
                                     (ct.SANPHAM.MaKH == u.MaKH || u.VaiTro == "Admin")
                                 )
                                 .ToList();

                    if (!dsCT.Any())
                    {
                        TempData["ThongBao"] = "Không có sản phẩm nào có thể huỷ.";
                        return RedirectToAction("CT_SanPhamDaBan", new { id });
                    }

                    foreach (var ct in dsCT)
                    {
                        ct.SANPHAM.SoLuong += ct.SoLuong;
                        ct.TrangThaiCT = "Đã Huỷ";
                    }

                    db.SaveChanges();

                    bool allCanceled = hd.CT_HOADON
                                         .All(ct => ct.TrangThaiCT == "Đã Huỷ");

                    if (allCanceled)
                    {
                        hd.TrangThai = "Đã Huỷ";
                        db.SaveChanges();
                    }

                    tran.Commit();

                    return RedirectToAction("SanPhamDaBan");
                }
                catch (Exception)
                {
                    tran.Rollback();
                    TempData["Error"] = "Có lỗi xảy ra khi huỷ hóa đơn.";
                    return RedirectToAction("SanPhamDaBan");
                }
            }
        }


        public ActionResult HoanThanh(int maHD, int maSP)
        {
            var ct = db.CT_HOADON
                       .FirstOrDefault(c => c.MaHD == maHD && c.MaSP == maSP);

            if (ct == null) return HttpNotFound();

            // Xác nhận chi tiết sản phẩm
            ct.TrangThaiCT = "Đã xác nhận";
            db.SaveChanges();

            // Lấy toàn bộ chi tiết hóa đơn
            var allCT = db.CT_HOADON.Where(c => c.MaHD == maHD).ToList();

            // Kiểm tra tất cả đã xác nhận hay chưa
            bool tatCa = allCT.All(c => c.TrangThaiCT == "Đã xác nhận");

            var hoaDon = db.HOADONs.Find(maHD);

            if (tatCa)
            {
                // Nếu tất cả SP đã được xác nhận → hoàn tất đơn
                hoaDon.TrangThai = "Đã thanh toán";
                hoaDon.NgayTT = DateTime.Now;
            }
            else
            {
                // Nếu chưa đủ → vẫn đang chờ xử lý
                hoaDon.TrangThai = "Đang chờ xử lý";
            }

            db.SaveChanges();

            TempData["ThongBao"] = "Xác nhận thành công!";
            return RedirectToAction("SanPhamDaBan");
        }


    }
}