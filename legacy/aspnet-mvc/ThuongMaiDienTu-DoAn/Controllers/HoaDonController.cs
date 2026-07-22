using System;
using System.Data.Entity;
using System.Drawing.Printing;
using System.IO;
using System.Linq;
using System.Web.Mvc;
using System.Xml.Linq;
using iTextSharp.text;
using iTextSharp.text.pdf;
using ThuongMaiDienTu_DoAn.Models;

namespace ThuongMaiDienTu_DoAn.Controllers
{
    public class HoaDonController : Controller
    {
        TMDTEntities db = new TMDTEntities();

        // ==============================
        // 1) KHÁCH ĐẶT HÀNG → TRỪ KHO NGAY
        // ==============================
        //public ActionResult DatHang()
        //{
        //    var kh = Session["user"] as NGUOIDUNG;
        //    if (kh == null) return RedirectToAction("DangNhap", "TaiKhoan");

        //    var gio = db.GIOHANGs
        //                .Include("CT_GIOHANG.SANPHAM")
        //                .FirstOrDefault(g => g.MaKH == kh.MaKH);

        //    if (gio == null || !gio.CT_GIOHANG.Any())
        //    {
        //        TempData["Error"] = "Giỏ hàng của bạn đang trống.";
        //        return RedirectToAction("Index", "GioHang");
        //    }

        //    // Tạo hóa đơn gốc
        //    var hd = new HOADON
        //    {
        //        MaKH = kh.MaKH,
        //        NgayDat = DateTime.Now,
        //        PhuongThucTT = "Thanh toán khi nhận hàng",
        //        TrangThai = "Đang chờ xử lý",
        //        TongTien = gio.CT_GIOHANG.Sum(c => c.ThanhTien),
        //        DiaChiGiaoHang = kh.DiaChi
        //    };
        //    db.HOADONs.Add(hd);
        //    db.SaveChanges(); // cần để có MaHD

        //    // Tạo chi tiết hóa đơn + TRỪ KHO
        //    foreach (var item in gio.CT_GIOHANG)
        //    {
        //        var sp = db.SANPHAMs.Find(item.MaSP);
        //        if (sp == null) continue;

        //        // ======== TRỪ KHO NGAY ========
        //        sp.SoLuong -= item.SoLuong;

        //        if (sp.SoLuong <= 0)
        //            sp.TrangThai = "Đã bán";

        //        db.CT_HOADON.Add(new CT_HOADON
        //        {
        //            MaHD = hd.MaHD,
        //            MaSP = item.MaSP,
        //            SoLuong = (int)item.SoLuong,
        //            ThanhTien = (decimal)item.ThanhTien,
        //            TrangThaiCT = "Chờ xác nhận",
        //            DaDanhGia = false
        //        });
        //    }

        //    // Xóa giỏ hàng
        //    db.CT_GIOHANG.RemoveRange(gio.CT_GIOHANG);
        //    db.SaveChanges();

        //    TempData["Success"] = "Đặt hàng thành công!";
        //    return RedirectToAction("LichSu", "TaiKhoan");
        //}

        public ActionResult DatHang()
        {
            var kh = Session["user"] as NGUOIDUNG;
            if (kh == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            var gio = db.GIOHANGs
                .Include(g => g.CT_GIOHANG.Select(c => c.SANPHAM))
                .FirstOrDefault(g => g.MaKH == kh.MaKH);

            if (gio == null || !gio.CT_GIOHANG.Any())
            {
                TempData["Error"] = "Giỏ hàng của bạn đang trống.";
                return RedirectToAction("Index", "GioHang");
            }

            using (var tran = db.Database.BeginTransaction())
            {
                try
                {
                    foreach (var item in gio.CT_GIOHANG)
                    {
                        var sp = db.SANPHAMs.Find(item.MaSP);
                        if (sp == null || sp.SoLuong < item.SoLuong)
                        {
                            tran.Rollback();
                            TempData["CartError"] = $"Sản phẩm \"{sp?.TenSP}\" đã hết hàng.";
                            return RedirectToAction("Index", "GioHang");
                        }
                    }

                    // Nhóm theo người bán
                    var groupBySeller = gio.CT_GIOHANG
                        .GroupBy(c => c.SANPHAM.MaKH)
                        .ToList();

                    // Mỗi người bán là 1 hoá đơn
                    foreach (var sellerGroup in groupBySeller)
                    {
                        var hd = new HOADON
                        {
                            MaKH = kh.MaKH,
                            NgayDat = DateTime.Now,
                            PhuongThucTT = "Thanh toán khi nhận hàng",
                            TrangThai = "Đang chờ xử lý",
                            TongTien = sellerGroup.Sum(c => c.ThanhTien),
                            DiaChiGiaoHang = kh.DiaChi
                        };

                        db.HOADONs.Add(hd);
                        db.SaveChanges(); 

                        foreach (var item in sellerGroup)
                        {
                            var sp = db.SANPHAMs.Find(item.MaSP);

                            sp.SoLuong -= item.SoLuong.Value;
                            if (sp.SoLuong == 0)
                                sp.TrangThai = "Đã bán";

                            db.CT_HOADON.Add(new CT_HOADON
                            {
                                MaHD = hd.MaHD,
                                MaSP = item.MaSP,
                                SoLuong = item.SoLuong.Value,
                                ThanhTien = item.ThanhTien.Value,
                                TrangThaiCT = "Chờ xác nhận",
                                DaDanhGia = false
                            });
                        }
                    }

                    db.CT_GIOHANG.RemoveRange(gio.CT_GIOHANG);
                    db.SaveChanges();
                    tran.Commit();

                    TempData["CartOK"] = "Đặt hàng thành công!";
                    return RedirectToAction("LichSu", "TaiKhoan");
                }
                catch
                {
                    tran.Rollback();
                    TempData["CartError"] = "Có lỗi xảy ra khi đặt hàng.";
                    return RedirectToAction("Index", "GioHang");
                }
            }
        }



        // ==============================
        // 2) NGƯỜI BÁN XÁC NHẬN TỪNG SẢN PHẨM
        // ==============================
        [HttpPost]
        public ActionResult XacNhanSanPham(int mahd, int masp)
        {
            var user = Session["user"] as NGUOIDUNG;
            if (user == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            var ct = db.CT_HOADON.Include("SANPHAM")
                                 .SingleOrDefault(c => c.MaHD == mahd && c.MaSP == masp);

            if (ct == null)
            {
                TempData["Loi"] = "Không tìm thấy sản phẩm.";
                return RedirectToAction("ChiTiet", new { id = mahd });
            }

            // chỉ chủ sản phẩm mới được xác nhận
            if (ct.SANPHAM.MaKH != user.MaKH)
            {
                TempData["Loi"] = "Bạn không có quyền xác nhận sản phẩm này.";
                return RedirectToAction("ChiTiet", new { id = mahd });
            }

            if (ct.TrangThaiCT == "Đã xác nhận")
            {
                TempData["Loi"] = "Sản phẩm này đã được xác nhận.";
                return RedirectToAction("ChiTiet", new { id = mahd });
            }

            // cập nhật trạng thái
            ct.TrangThaiCT = "Đã xác nhận";
            db.SaveChanges();

            // kiểm tra còn sản phẩm nào chưa xác nhận không?
            bool conCho = db.CT_HOADON
                            .Where(x => x.MaHD == mahd)
                            .Any(x => x.TrangThaiCT == "Chờ xác nhận");

            var hd = db.HOADONs.Find(mahd);

            if (!conCho)
            {
                hd.TrangThai = "Đã thanh toán";
                hd.NgayTT = DateTime.Now;
                db.SaveChanges();
                TempData["OK"] = "Tất cả sản phẩm đã xác nhận. Đơn hàng hoàn tất!";
            }
            else
            {
                TempData["OK"] = "Xác nhận thành công! Vẫn còn sản phẩm chưa xác nhận.";
            }

            return RedirectToAction("ChiTiet", new { id = mahd });
        }


        // ==============================
        // 3) HỦY ĐƠN → HOÀN KHO
        // ==============================
        [HttpPost]
        public ActionResult HuyDon(int id)
        {
            var user = Session["user"] as NGUOIDUNG;
            if (user == null)
                return RedirectToAction("DangNhap", "TaiKhoan");

            var hd = db.HOADONs
                      .Include("CT_HOADON.SANPHAM")
                      .FirstOrDefault(h => h.MaHD == id && h.MaKH == user.MaKH);

            if (hd == null)
            {
                TempData["Error"] = "Không tìm thấy đơn hàng.";
                return RedirectToAction("LichSu", "TaiKhoan");
            }

            // ❗ Chỉ cho phép hủy khi chưa người bán xác nhận
            if (hd.TrangThai != "Đang chờ xử lý" &&
                hd.TrangThai != "Chờ người bán xác nhận đủ")
            {
                TempData["Error"] = "Đơn này không thể hủy.";
                return RedirectToAction("LichSu", "TaiKhoan");
            }

            // 🔥 TRẢ LẠI TỒN KHO
            foreach (var ct in hd.CT_HOADON)
            {
                var sp = ct.SANPHAM;
                sp.SoLuong += ct.SoLuong;

                if (sp.SoLuong > 0)
                    sp.TrangThai = "Đang bán"; // cập nhật lại để người khác thấy
            }

            // 🔥 CẬP NHẬT TRẠNG THÁI ĐƠN
            hd.TrangThai = "Đã hủy";
            db.SaveChanges();

            TempData["Success"] = "Đơn hàng đã được hủy thành công!";
            return RedirectToAction("LichSu", "TaiKhoan");
        }



        // ==============================
        // 4) XEM DANH SÁCH ĐƠN HÀNG (KHÁCH HOẶC NGƯỜI BÁN)
        // ==============================
        public ActionResult ChiTiet(int id)
        {
            var hd = db.HOADONs
                       .Include("CT_HOADON.SANPHAM")
                       .FirstOrDefault(h => h.MaHD == id);

            if (hd == null) return RedirectToAction("LichSu", "TaiKhoan");

            return View(hd);
        }

        public ActionResult InHoaDon(int id)
        {
            var hd = db.HOADONs
                .Include("NGUOIDUNG")
                .Include("CT_HOADON.SANPHAM")
                .FirstOrDefault(x => x.MaHD == id);

            if (hd == null)
                return HttpNotFound();

            if (hd.TrangThai != "Đã thanh toán" ||
                !hd.CT_HOADON.All(ct => ct.TrangThaiCT == "Đã xác nhận"))
            {
                TempData["Error"] = "Hoá đơn chưa thể in do chưa hoàn tất xác nhận!";
                return RedirectToAction("ChiTiet", new { id });
            }


            string fontPath = Server.MapPath("~/Content/fonts/DejaVuSans.ttf");
            BaseFont bf = BaseFont.CreateFont(fontPath, BaseFont.IDENTITY_H, BaseFont.EMBEDDED);

            Font fontTitle = new Font(bf, 20, Font.BOLD);
            Font fontBold = new Font(bf, 12, Font.BOLD);
            Font fontNormal = new Font(bf, 11, Font.NORMAL);

            MemoryStream workStream = new MemoryStream();
            Document document = new Document(PageSize.A4, 40, 40, 40, 40);
            PdfWriter.GetInstance(document, workStream).CloseStream = false;

            document.Open();
            document.Add(new Paragraph("SÀN GIAO DỊCH TECHSECOND", fontBold));
            document.Add(new Paragraph("HOÁ ĐƠN BÁN HÀNG", fontTitle));
            document.Add(new Paragraph(" ", fontNormal));
            document.Add(new Paragraph("Mã hoá đơn: #" + hd.MaHD, fontNormal));
            document.Add(new Paragraph("Ngày lập: " + (hd.NgayDat?.ToString("dd/MM/yyyy HH:mm") ?? "-"), fontNormal));
            document.Add(new Paragraph("Ngày thanh toán: " + (hd.NgayTT?.ToString("dd/MM/yyyy HH:mm") ?? "-"), fontNormal));
            document.Add(new Paragraph("Địa chỉ giao hàng: " + hd.DiaChiGiaoHang, fontNormal));
            document.Add(new Paragraph(" ", fontNormal));
            document.Add(new Paragraph("Người mua: " + hd.NGUOIDUNG.HoTen, fontNormal));
            var nguoiBanList = hd.CT_HOADON
                    .Select(ct => ct.SANPHAM.NGUOIDUNG.HoTen)
                    .Distinct()
                    .ToList();
            string nguoiBanText = nguoiBanList.Count == 1
                ? nguoiBanList.First()
                : string.Join(", ", nguoiBanList);
            document.Add(new Paragraph("Người bán: " + nguoiBanText, fontNormal));

            document.Add(new Paragraph(" ", fontNormal));
            PdfPTable table = new PdfPTable(4);
            table.WidthPercentage = 100;
            table.SetWidths(new float[] { 40f, 15f, 22f, 23f });

            string[] headers = { "Tên sản phẩm", "Số lượng", "Đơn giá", "Thành tiền" };

            foreach (var h in headers)
            {
                PdfPCell cell = new PdfPCell(new Phrase(h, fontBold));
                cell.BackgroundColor = new BaseColor(230, 230, 230);
                cell.HorizontalAlignment = Element.ALIGN_CENTER;
                cell.Padding = 5;
                table.AddCell(cell);
            }

            foreach (var ct in hd.CT_HOADON)
            {
                table.AddCell(new PdfPCell(new Phrase(ct.SANPHAM.TenSP, fontNormal)) { Padding = 5 });
                table.AddCell(new PdfPCell(new Phrase(ct.SoLuong.ToString(), fontNormal)) { Padding = 5, HorizontalAlignment = Element.ALIGN_CENTER });
                table.AddCell(new PdfPCell(new Phrase(string.Format("{0:N0} đ", ct.SANPHAM.Gia), fontNormal)) { Padding = 5, HorizontalAlignment = Element.ALIGN_RIGHT });
                table.AddCell(new PdfPCell(new Phrase(string.Format("{0:N0} đ", ct.ThanhTien), fontNormal)) { Padding = 5, HorizontalAlignment = Element.ALIGN_RIGHT });
            }
            document.Add(table);
            document.Add(new Paragraph(" ", fontNormal));
            document.Add(new Paragraph("Tổng cộng: " + string.Format("{0:N0} đ", hd.TongTien), fontBold));
            document.Add(new Paragraph(" ", fontNormal));
            document.Add(new Paragraph("Cảm ơn bạn đã mua hàng tại TechSecond!", fontNormal));
            document.Close();
            byte[] bytes = workStream.ToArray();
            workStream.Write(bytes, 0, bytes.Length);
            workStream.Position = 0;
            return File(workStream, "application/pdf", $"HoaDon_{hd.MaHD}.pdf");
        }
    }
}
