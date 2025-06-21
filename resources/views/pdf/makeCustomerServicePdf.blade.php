<html>
    <head>
        <style>
            @page { margin: 120px 30px 30px 50px; }
            #header1 { position: fixed; left: 0px; top: -110px; right: 0px; text-align: center; }
            .title-table { margin-top: 10px; margin-bottom: 10px; }

            #content { margin: 10px 15px 180px 5px; }
            body {
                background-color: transparent;
                color: black;
                font-family: "verdana", "sans-serif";
                margin: 0px;
                padding-top: 0px;
                font-size: .75em;
            }

            .header {
                width: 100%;
                margin-bottom: 0px; /* Adjust spacing between logo and title */
                border-bottom: 2px solid #000; /* Full-width line */
                padding-bottom: 0; /* Adjust line gap */
            }

            .header table {
                margin-bottom: 0px; /* Adjust the margin between logo and title */
                padding: 0;
            }

            .header th {
                padding: 0; /* Remove padding around header elements */
                margin: 0;  /* Remove margin */
            }

            /* Full-width line under logo */
            .header hr {
                border: 0;
                border-top: 1px solid #000;
                width: 100%; /* Ensure the line spans the full width */
                margin-top: 5px; /* Adjust margin between content and line */
            }

            .header th, .header td {
                padding: 0;
                margin: 0;
                line-height: 1; /* Ensure compact line height */
            }

            .header h {
                margin: 0; /* Remove any margins from h tags */
                padding: 0;
            }

            /* Ensure content is aligned properly */
            .header td {
                padding: 0;
                margin: 0;
            }

            .header img {
                margin-bottom: 0px; /* Remove any margin under the logo */
                padding-bottom: 0px;
            }
        </style>
    </head>
    <body>
        <div id="header1">
            @include('partials.header') <!-- Include header blade file -->
        </div>

        @if($data['warranty'] == 1)
            <table width="100%" class="title-table">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th style="font-size:16px"><strong><u>RMA RECEIVING</u></strong></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
            </table>
        @else
            <table width="100%" class="title-table">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th style="font-size:16px"><strong><u>COLLECTION SLIP</u></strong></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
            </table>
        @endif

        <table style="width:100%">
            <tr>
                <td width="70" style="font-weight:bold;">Date</td>
                <td width="3">:</td>
                <td>{{ $date_create }}</td>
                <td width="70">&nbsp;</td>
                <td width="3">&nbsp;</td>
                <td>&nbsp;</td>
                <td width="70" rowspan="2" style="font-weight:bold;">RMA No.</td>
                <td width="3" rowspan="2">:</td>
                <td rowspan="2"><b style="font-size:20px">{{ $data['rma_num'] }}</b></td>
            </tr>
            <tr>
                <td width="70" style="font-weight:bold;">Company</td>
                <td width="3">:</td>
                <td>{{ $data['company'] }}</td>
                <td width="70">&nbsp;</td>
                <td width="3">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>

        <br><br>
        <table style="width:100%">
            <tr>
                <td width="70" style="font-weight:bold;">Nomor</td>
                <td width="3">:</td>
                <td>{{ $data['cs_code'] }}</td>
                <td width="70" style="font-weight:bold;">Phone</td>
                <td width="3">:</td>
                <td>{{ $data['phone'] }}</td>
            </tr>
            <tr>
                <td width="70" style="font-weight:bold;">Customer</td>
                <td width="3">:</td>
                <td>{{ $data['customer_name'] }} ({{ $data['accountNum'] }})</td>
                <td width="70" style="font-weight:bold;">Email</td>
                <td width="3">:</td>
                <td>{{ $data['email'] }}</td>
            </tr>
            <tr>
                <td width="70" style="font-weight:bold;">Address</td>
                <td width="3">:</td>
                <td>{{ $data['address'] }}</td>
                <td width="70" style="font-weight:bold;">NPWP</td>
                <td width="3">:</td>
                <td>{{ $data['npwp'] }}</td>
            </tr>
        </table>

        <br><br>
        <table style="width:100%">
            <tr>
                <td width="70" style="font-weight:bold;">Brand</td>
                <td width="3">:</td>
                <td>{{ $data['brand'] }}</td>
                <td width="70" style="font-weight:bold;">Warranty</td>
                <td width="3">:</td>
                <td>{{ $data['warranty'] }}</td>
                <td width="70" style="font-weight:bold;">Technician</td>
                <td width="3">:</td>
                <td>{{ $data['technician_name'] }}</td>
            </tr>
            <tr>
                <td width="70" style="font-weight:bold;">Model</td>
                <td width="3">:</td>
                <td>{{ $data['model'] }}</td>
                <td width="70" style="font-weight:bold;">Date Warranty</td>
                <td width="3">:</td>
                <td>{{ $date_warranty }}</td>
                <td width="70" style="font-weight:bold;">Problem</td>
                <td width="3">:</td>
                <td>{{ $data['problem'] }}</td>
            </tr>
            <tr>
                <td width="70" style="font-weight:bold;">Category</td>
                <td width="3">:</td>
                <td>{{ $data['category'] }}</td>
                <td width="70" style="font-weight:bold;">Incoming Source</td>
                <td width="3">:</td>
                <td>{{ $data['incoming_source'] }}</td>
                <td width="70" style="font-weight:bold;">Job Request</td>
                <td width="3">:</td>
                <td>{{ $data['job_request'] }}</td>
            </tr>
            <tr>
                <td width="70" style="font-weight:bold;">Type</td>
                <td width="3">:</td>
                <td>{{ $data['type'] }}</td>
                <td width="70" style="font-weight:bold;">Service Type</td>
                <td width="3">:</td>
                <td>{{ $data['service_type'] }}</td>
                <td width="70" style="font-weight:bold;">Description</td>
                <td width="3">:</td>
                <td>{{ $data['note'] }}</td>
            </tr>
            <tr>
                <td width="70" style="font-weight:bold;">Serial Number</td>
                <td width="3">:</td>
                <td>{{ $data['serial_number'] }}</td>
                <td width="70" style="font-weight:bold;">Cost Service</td>
                <td width="3">:</td>
                <td>{{ number_format($data['cost_service']) }}</td>
            </tr>
        </table>

        <br><br>

        <table width="100%" style="font-size: 10px;text-align: justify;">
            <tr>
                <td colspan="2"><b>Ketentuan dan persyaratan:</b></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td width="1%">1.</td>
                <td width="99%">Untuk kasus unit mati total/layar rusak tidak bisa dijamin komponen lainnya berfungsi normal, analisa lengkap akan dilakukan  setelah unit sudah bisa di hidupkan</td>
            </tr>
            <tr>
                <td width="1">2.</td>
                <td>Pemeriksaan analisa kerusakan  diperkirakan dalam waktu 1(satu) sampai dengan 3 (tiga) hari kerja.</td>
            </tr>
            <tr>
                <td width="1">3.</td>
                <td>Biaya perbaikan dan penggantian suku cadang akan diinformasikan terlebih dahulu sebelum pengerjaan.</td>
            </tr>
            <tr>
                <td width="1">4.</td>
                <td>Apabila penawaran telah disetujui maka suku cadang yang telah dipesan tidak dapat dibatalkan dengan alasan apapun.</td>
            </tr>
            <tr>
                <td width="1">5.</td>
                <td>Apabila konfirmasi perbaikan  (penawaran service) tidak ditanggapi selama 14 (empat belas) hari kerja, maka perbaikan dianggap batal.</td>
            </tr>
            <tr>
                <td width="1">6.</td>
                <td>Ada beberapa kerusakan lain yang baru bisa diketahui setelah pengerjaan service atau pengecekan, dan untuk kerusakan lain yang ditemukan akan dikenakan biaya tambahan dengan persetujuan pelanggan.</td>
            </tr>
            <tr>
                <td width="1">7.</td>
                <td>Pembatalan transaksi perbaikan akan dikenakan biaya diagnosa pemeriksaan sebesar 50% dari biaya service yang tercantum di penawaran.</td>
            </tr>
            <tr>
                <td width="1">8.</td>
                <td>Customer setuju dan tidak akan menuntut apa pun serta menyerahkan proses <i>Disposal</i> (pemusnahan)  terhadap unit yang di service bila tidak diambil lebih dari 3 bulan setelah ada keputusan status service (cancel/ready unit) yang sudah disampaikan oleh pihak service center melalui nomor whatapp yang tertera di dokumen collection slip service.</td>
            </tr>
            <tr>
                <td width="1">9.</td>
                <td>Untuk estimasi biaya perbaikan yang lebih dari Rp1.000.000,- , kami menerapkan biaya komitment service sebesar 50% dari total biaya service. (kecuali ada kesepakatan kerjasama sebelumnya).</td>
            </tr>
            <tr>
                <td width="1">10.</td>
                <td>Proses service akan kami lanjutkan setelah biaya komitment service telah terverifikasi oleh bagian keuangan</td>
            </tr>
            <tr>
                <td width="1">11.</td>
                <td>Suku cadang dengan status <i>indent</i>, maka pemesanan suku cadang akan kami teruskan ke pihak EPSON Indonesia , dengan estimasi waktu pemesanan 4 sampai 16 minggu.</td>
            </tr>
            <tr>
                <td width="1">12.</td>
                <td>Waktu pengambilan unit service , untuk customer yang melakukan pelunasan/pembayaran  via transfer Bank wajib membawa bukti transfer bank.</td>
            </tr>
            <tr>
                <td width="1">13.</td>
                <td>Pembayaran biaya service dapat dilakukan melalui transfer bank atau secara tunai/debit di EPSON service center kami.</td>
            </tr>
            <tr>
                <td width="1">14.</td>
                <td>Untuk permintaan penerbitan faktur Pajak, dapat mengirimkan data NPWP & INVOICE, dan dikirimkan ke bagian pajak via email <u style="color:blue;">pajak@xyzgoprint.com.</u></td>
            </tr>
            <tr>
                <td width="1">15.</td>
                <td>Pelanggan wajib memeriksa kembali kondisi unit dan kelengkapan saat pengambilan. Keluhan terhadap kondisi dan kelengkapan unit setelah meninggalkan gerai kami tidak dapat dilayani Kembali.</td>
            </tr>
            <tr>
                <td width="1">16.</td>
                <td>Lembar ini merupakan bukti pengambilan unit dan harap disimpan dengan baik. Segala resiko yang diakibatkan oleh hilangnya lembar ini adalah diluar tanggung jawab kami.</td>
            </tr>
            <tr>
                <td width="1">17.</td>
                <td><b>Pelanggan yang menandatangani lembar ini telah memahami dan menyetujui syarat dan ketentuan di atas.</b></td>
            </tr>
        </table>

        <div id="footer">
            <br><br>
            <table width="100%">
                <tr>
                    <td style="font-size: 10px;text-align: justify;">
                        <b>Note : Saya sudah membaca  dan menyetujui semua ketentuan dan persyaratan diatas.</b>
                    </td>
                </tr>
            </table>
            <br><br>
            <table style="margin-left: auto; margin-right: auto;" width="80%">
                <tr>
                    <td style="width:50; text-align: center;">Operated by </td>
                    <td style="width:30; text-align: center;">&nbsp;</td>
                    <td rowspan="2" style="width:50; text-align: center;">Customer</td>
                </tr>
                <tr>
                    <td style="width:50; text-align: center; font-size: 10px;"><b>PT XYZGOPRINT INDONESIA</b></td>
                </tr>
                <tr>
                    <td style="height:60; text-align: center;">&nbsp;</td>
                    <td style="height:60; text-align: center;">&nbsp;</td>
                    <td style="height:60; text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:50; border-top: 1px solid; text-align: center;">{{ $saved[0]->name }}</td>
                    <td style="width:30; text-align: center;">&nbsp;</td>
                    <td style="width:50; border-top: 1px solid; text-align: center;">{{ $data['customer_name'] }}</td>
                </tr>
            </table>
        </div>

        @include('partials.end') <!-- Include end blade file -->
    </body>
</html>
