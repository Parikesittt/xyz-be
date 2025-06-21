<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Service Quotation</title>
    <style>
        body { font-family: "Arial", sans-serif; font-size: 12px; }
        @page { margin: 50px 30px 30px 50px; }
        #header1 { left: 0px; top: -170px; right: 0px; text-align: center; }
        #content { margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 8px; border: 1px solid #ddd; text-align: left; }
        .info-table td { border:none; padding:2px 4px; font-size:12px; }
        .info-table { width:100%; margin-bottom:8px; }
        .service-table th, .service-table td { padding:4px 6px; font-size:12px; }
        .service-table th { background:#f0f0f0; font-weight:bold; }
        .service-table td.price, .service-table td.total { text-align:right; }
        footer {
                position: fixed;
                bottom: 100px;
                left: 0px;
                right: 0px;
                height: 50px;
            }
    </style>
</head>
<body>
    <div id="header1">
        <table width="100%">
            <tr>
                <th style="font-size:20px;text-align:left"><strong>Quotation Service</strong></th>
                <th></th>
                <th style="text-align:right"><img src="{{ $company_logo }}" height="60" width="120" /></th>
            </tr>
        </table>
    </div>



    <div id="content">
        <table class="info-table">
            <tr>
                <td>Date</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::now()->format('d M Y') }}</td>
            </tr>
        </table>

        <p><strong>Dear Customer,</strong></p>
        <p>Terima kasih sudah memberi kepercayaan kepada team XYZGOPRINT ( EPSON AUTHORIZED SERVICE PATHNER) menjadi bagian mitra kerja Bapak/Ibu , bersama ini kami laporkan status service unit Bapak/Ibu beserta biaya perbaikannya , dengan perincian sebagai berikut:</p>

        <table class="info-table">
            <tr>
                <td><b>Service No</b></td><td>:</td><td><b>{{ $data['cs_code'] }}</b></td>
            </tr>
            <tr>
                <td>A/n</td><td>:</td><td>{{ $data['customer_name'] }}</td>
                <td>Phone</td><td>:</td><td>{{ $data['phone'] }}</td>
            </tr>
            <tr>
                <td>Address</td><td>:</td><td>{{ $data['customer_address'] }}</td>
                <td>Email</td><td>:</td><td>{{ $data['email'] }}</td>
            </tr>
            <tr><td colspan="3"><b>MACHINE SPECIFICATIONS:</b></td></tr>
            <tr>
                <td>Category</td><td>:</td><td>{{ $data['category'] }}</td>
                <td>Model</td><td>:</td><td>{{ $data['model'] }}</td>
            </tr>
            <tr>
                <td>Brand</td><td>:</td><td>{{ $data['brand'] }}</td>
                <td>Serial Number</td><td>:</td><td>{{ $data['serial_number'] }}</td>
            </tr>
            <tr>
                <td>Type</td><td>:</td><td>{{ $data['type'] }}</td>
                <td>Warranty</td><td>:</td>
                <td><b>{{ $data['warranty'] == 1 ? 'Yes' : 'No' }}</b></td>
            </tr>
        </table>


        <hr>

        <h3>Service Details</h3>
        <table class="service-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Service Detail</th>
                    <th>Status Part</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; $total_all = 0; @endphp
                @foreach ($datas as $item)
                    @if (($item->part_active ?? 0) < 9)
                        <tr>
                            <td style="text-align:center;">{{ $no++ }}</td>
                            <td>{{ $item->part_name }}</td>
                            <td style="text-align:center;">{{ $item->status_stock }}</td>
                            <td style="text-align:center;">{{ $item->qty }}</td>
                            <td style="text-align:right;">{{ number_format($item->price) }}</td>
                            <td style="text-align:right;">{{ number_format($item->price * $item->qty) }}</td>
                        </tr>
                        @php $total_all += $item->price * $item->qty; @endphp
                    @endif
                @endforeach
                <tr>
                    <td style="text-align:center;">{{ $no++ }}</td>
                    <td>SERVICE</td>
                    <td></td>
                    <td style="text-align:center;">1</td>
                    <td style="text-align:right;">{{ number_format($service_cost) }}</td>
                    <td style="text-align:right;">{{ number_format($service_cost) }}</td>
                </tr>
                @if ($data['incoming_source'] == 'ON-SITE')
                <tr>
                    <td style="text-align:center;">{{ $no++ }}</td>
                    <td>COST ONSITE</td>
                    <td></td>
                    <td style="text-align:center;">1</td>
                    <td style="text-align:right;">{{ number_format($onsite_cost) }}</td>
                    <td style="text-align:right;">{{ number_format($onsite_cost) }}</td>
                </tr>
                @endif

                {{-- Subtotal, Pajak, Total --}}
                @if ($is_ppn > 0)
                    <tr>
                        <td colspan="5" style="text-align:right;"><b>Subtotal (Rp)</b></td>
                        <td style="text-align:right;font-weight:bold;">{{ number_format($subtotal) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align:right;"><b>Pajak (Rp)</b></td>
                        <td style="text-align:right;font-weight:bold;">{{ number_format($tax) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align:right;"><b>Total Keseluruhan (Rp)</b></td>
                        <td style="text-align:right;font-weight:bold;">{{ number_format($total_all_final) }}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="5" style="text-align:right;"><b>Total Keseluruhan (Rp)</b></td>
                        <td style="text-align:right;font-weight:bold;">{{ number_format($subtotal) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
        @if ($dp_required)
            <div style="margin-top:10px; font-size:12px;">
                <p>Untuk meningkatkan komitmen terhadap perbaikan unit di atas, kami berharap dapat dibantu uang muka (<i>Down Payment</i>) sebesar 50% dari biaya suku cadang sesuai dengan penawaran di atas.</p>
                <ul>
                    <li>Metode Pembayaran: (Cash/Debit/Transfer)</li>
                    <li>Total DP dari suku cadang sebesar 50%: <b>Rp {{ number_format($dp_amount) }},-</b></li>
                    <li>No. Rekening Bank: {{ $bank_account->bank }} . {{ $bank_account->no_rek }}</li>
                    <li>Atas Nama: {{ $bank_account->name }}</li>
                </ul>
                @if(isset($data['is_not_dp']) && $data['is_not_dp'] > 0)
                    <b>Kami ingin menginformasikan bahwa bagi pelanggan yang telah menjalin kerjasama dengan XYZ, permintaan untuk pembayaran uang muka dapat diabaikan.</b>
                @endif
            </div>
        @endif

        <div style="margin-top:10px; font-size:12px;">
            <p>Selanjutnya untuk melanjutkan proses perbaikan ini kami membutuhkan persetujuan Bapak/Ibu untuk penawaran perbaikan unit ini dengan biaya tersebut diatas, atau bisa di lakukan dengan melakukan reply email ini atau dengan menghubungi layanan call center kami di 021 8835-4971 / 021 8849-084 atau dapat melalui email ke cs@xyzgoprint.com.</p>
            <p>Terima kasih atas perhatian Bapak/Ibu dan kerjasamanya. Bila ada informasi lain yang ingin di tanyakan mohon jangan sungkan menghubungi kami.</p>
            <p>Syarat dan Ketentuan : </p>
            <ol style="margin-left:16px;">
                <li><b>Berlaku 14 Hari dari Tanggal Penawaran</b>
                    <p>Penawaran ini berlaku selama 14 (empat belas) hari kalender terhitung sejak tanggal penawaran diberikan. Jika pelanggan memutuskan untuk membatalkan layanan, akan <b>biaya pembatalan sebesar 50% dari biaya service yang berlaku</b>, sesuai dengan jenis layanan yang diberikan. Biaya pembatalan ini wajib dibayar pada saat pengambilan unit dengan status pembatalan.</p>
                </li>
                <li><b>Pengambilan Unit yang Dibatalkan</b>
                    <p>Jika lebih dari 14 (empat belas) hari kalender sejak penawaran diberikan tidak ada konfirmasi atau tanggapan dari pelanggan, maka dianggap bahwa pelanggan tidak setuju untuk melanjutkan perbaikan dan unit tersebut akan secara otomatis dianggap dibatalkan oleh sistem. Untuk unit yang dibatalkan, pelanggan diharapkan untuk segera mengambil unit tersebut dalam waktu maksimal 7 (tujuh) hari kerja sejak tanggal penawaran.</p>
                </li>
                <li><b>Pengambilan Unit dengan Status CANCEL</b>
                    <p>Pelanggan dimohon untuk segera mengambil unit yang memiliki status "CANCEL" dengan menunjukkan quotation slip asli yang telah ditandatangani.</p>
                </li>
                <li><b>Keterlambatan Pengambilan Unit</b>
                    <p>Apabila unit yang telah dikonfirmasi status servicenya tidak diambil dalam jangka waktu lebih dari 14 (empat belas) hari kerja sejak pemberitahuan konfirmasi status dikirimkan oleh sistem melalui aplikasi WhatsApp (WA), maka unit tersebut akan dianggap sebagai unit yang tidak diambil dan akan tunduk pada ketentuan pembatalan yang berlaku.</p>
                    <p>Pihak XYZ tidak bertanggung jawab atas kerusakan maupun kehilangan yang mungkin terjadi di kemudian hari terhadap unit tersebut. Apabila unit tidak diambil dalam waktu lebih dari 30 (tiga puluh) hari kalender sejak konfirmasi status service terakhir, maka pelanggan setuju untuk memberikan wewenang penuh kepada pihak XYZ untuk memusnahkan unit tersebut tanpa pemberitahuan lebih lanjut mengenai waktu pemusnahan, dan dengan ini membebaskan pihak XYZ dari segala bentuk tuntutan atau klaim di kemudian hari dengan alasan apapun.</p>
                </li>
                <li><b>Penawaran Bersifat Estimasi</b>
                    <p>Penawaran yang diberikan merupakan estimasi biaya dan tidak bersifat final. Kebutuhan suku cadang yang tercantum dalam penawaran ini berdasarkan analisis teknisi pada saat pengecekan dilakukan. Namun demikian, tidak menutup kemungkinan adanya penambahan suku cadang lainnya yang diperlukan selama proses perbaikan. Jika ada penambahan biaya suku cadang, kami akan mengajukan penawaran terpisah untuk persetujuan lebih lanjut dari pihak pelanggan.</p>
                </li>
                <li><b>Waktu Tunggu Suku Cadang Status Indent</b>
                    <p>Estimasi waktu tunggu untuk suku cadang dengan status indent adalah sekitar 4 (empat) hingga 16 (enam belas) minggu. Waktu tersebut dihitung mulai dari konfirmasi persetujuan yang diberikan oleh pelanggan dan merupakan estimasi waktu normal yang bergantung pada ketersediaan suku cadang tersebut dari prinsipal EPSON.</p>
                </li>
                <li><b>Down Payment (DP) Tidak Dapat Dikembalikan</b>
                    <p>Down Payment (DP) yang telah dibayarkan oleh pelanggan tidak dapat dikembalikan dalam kondisi apapun.</p>
                </li>
                <li><b>Penawaran yang Telah Disetujui Tidak Dapat Dibatalkan</b>
                    <p>Setelah penawaran disetujui oleh pelanggan, penawaran tersebut.</p>
                </li>
                <li><b>Penyalahgunaan data</b>
                    <p>Pihak XYZ berkomitmen untuk menjaga dan melindungi data pelanggan sebaik-baiknya. Namun, apabila terjadi kebocoran data atau penggunaan data oleh pihak ketiga tanpa sepengetahuan dan/atau tanpa persetujuan dari pihak XYZ, maka pihak XYZ tidak dapat dimintai pertanggungjawaban dalam bentuk apapun. Segala bentuk penyalahgunaan data yang terjadi di luar kendali dan tanpa keterlibatan langsung dari pihak XYZ sepenuhnya berada di luar tanggung jawab pihak XYZ. Oleh karena itu, pihak XYZ dibebaskan dari segala bentuk tuntutan, gugatan, maupun klaim yang timbul akibat penyalahgunaan data tersebut.</p>
                </li>
                <li><b>Pernyataan Persetujuan dan Pemberian Kuasa</b>
                </li>
            </ol>
            <p>Saya dengan ini menyatakan setuju dan memberikan kuasa penuh kepada pihak XYZ untuk menangani unit service dengan detail sebagai berikut:</p>
            <table class="info-table">
                <tr>
                    <td>Nomor Service</td><td>:</td><td>{{ $data['cs_code'] }}</td>
                </tr>
                <tr>
                    <td>Model</td><td>:</td><td>{{ $data['model'] }}</td>
                </tr>
                <tr>
                    <td>Serial Number</td><td>:</td><td>{{ $data['serial_number'] }}</td>
                </tr>
            </table>
            <p>Apabila unit tersebut tidak diambil lebih dari 3 (tiga) bulan terhitung sejak tanggal pemberitahuan penawaran terakhir (reminder 3) yang dikirimkan oleh pihak XYZ melalui aplikasi WhatsApp (WA), maka pihak XYZ berhak untuk memusnahkan unit tersebut. Selanjutnya, pihak XYZ akan terbebas dari segala tuntutan atau klaim apapun yang munkin timbul di kemudian hari terkait dengan unit tersebut.</p>
            <p>Bersama ini saya sudah membaca, mengerti dan menyetujui semua persyaratan service di atas</p>
            <br>
            <br>
            <br>
            <p>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
            <table class="info-table">
                <tr>
                    <td>Nama</td><td>:</td><td>{{ $data['customer_name'] }}</td>
                </tr>
                <tr>
                    <td>No Telpon</td><td>:</td><td>{{ $data['phone'] }}</td>
                </tr>
            </table>
        </div>

        <hr>


        <footer>
                <table width="100%" style="border:1px solid;">
                    <tr>
                        <td style="border-right:1px solid;text-align:center;vertical-align:top;" width="50%">
                            Customer Service<br>
                            <img src="data:image/png;base64,{{ $qrcode }}" width="100" height="100" /><br>
                            ({{ $customer_service_name }})
                        </td>
                        <td style="vertical-align:top;">
                            <a href="https://{{ $website }}">{{ $website }}</a><br>
                            <b>EPSON AUTHORIZED SERVICE & DEALER</b><br>
                            <ul style="margin:0; padding-left:16px;">
                                @foreach($footer_list as $item)
                                    <li style="font-size:11px;">{{ $item }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                </table>
        </footer>

    </div>
</body>
</html>
