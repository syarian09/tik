<!DOCTYPE html>
<html>

<head>
  <title>{{ $title }}</title>
  <style type="text/css">
    @page {
      margin: 1.5cm;
      margin-bottom: 1cm;
      margin-top: 1.5cm;
      font-family: Helvetica, Arial, sans-serif;
      font-size: 10pt;
    }
  </style>
</head>

<body>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" style="background-color: black; color: white; padding-top: 10px; padding-bottom: 10px">
        <font style="font-weight:bold; font-size: 16px;">{{ $title }}</font>
      </td>
    </tr>
  </table>

  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 20px; margin-bottom: 20px;">
    <tr>
      <td style="width: 10%">Nama Mapel </td>
      <td style="width: 2%">:</td>
      <td><b>Bimbingan TIK</b></td>
    </tr>
    <tr>
      <td style="width: 10%">Kelas </td>
      <td style="width: 2%">:</td>
      <td>{{ Str::of($kelas)->substr(0, 7) }}</td>
    </tr>
  </table>

  <div>{!! str_replace('../../storage/', 'storage/', $data->isi_materi) !!}</div>
</body>

</html>