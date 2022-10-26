<?php $this->layout("_theme", ["title"=>"a"]); ?>

<p>Boa tarde <?= $responsavel?>,</p><br>
<p>Estou lhe encaminhando o CTe  <?= $cte ?> referente ao transporte conforme abaixo:</p><br>

<table border='0' cellspacing='0' cellpadding='0' width='0' style='border-collapse:collapse'>
    <tbody>
        <tr style='height:15.75pt'>
            <td valign='bottom' style='border:solid white 1.0pt;background:#434343;padding:1.5pt 2.25pt 1.5pt 2.25pt;height:15.75pt'>
                <p class='MsoNormal' align='center' style='text-align:center'>
                    <b>
                        <span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:yellow'>&lt;&lt;&lt;&lt;&lt;&lt; <?= str_mes_ano()?> &gt;&gt;&gt;&gt;&gt;<u></u><u></u></span>
                    </b>
                </p>
            </td>
            <td valign='bottom' style='border:solid white 1.0pt;border-left:none;background:#434343;padding:1.5pt 2.25pt 1.5pt 2.25pt;height:15.75pt'>
                <p class='MsoNormal' align='center' style='text-align:center'>
                    <b>
                        <span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:#ff9900'>EMPRESA<u></u><u></u></span>
                    </b>
                </p>
            </td>
            <td valign='bottom' style='border:solid white 1.0pt;border-left:none;background:#434343;padding:1.5pt 2.25pt 1.5pt 2.25pt;height:15.75pt'>
                <p class='MsoNormal' align='center' style='text-align:center'>
                    <b>
                        <span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:#ff9900'>VALOR DO FRETE<u></u><u></u></span>
                    </b>
                </p>
            </td>
            <td valign='bottom' style='border:solid white 1.0pt;border-left:none;background:#434343;padding:1.5pt 2.25pt 1.5pt 2.25pt;height:15.75pt'>
                <p class='MsoNormal' align='center' style='text-align:center'>
                    <b>
                        <span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:#ff9900'>ORIGEM <u></u><u></u></span>
                    </b>
                </p>
            </td>
            <td valign='bottom' style='border:solid white 1.0pt;border-left:none;background:#434343;padding:1.5pt 2.25pt 1.5pt 2.25pt;height:15.75pt'>
                <p class='MsoNormal' align='center' style='text-align:center'>
                    <b>
                        <span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:#ff9900'>DESTINO<u></u><u></u></span>
                    </b>
                </p>
            </td>
        </tr>
        <tr style='height:15.75pt'>
            <td valign='bottom' style='border:solid #cccccc 1.0pt;border-top:none;background:#4a86e8;padding:1.5pt 2.25pt 1.5pt 2.25pt;height:15.75pt'>
                <p class='MsoNormal' align='center' style='text-align:center'>
                    <b>
                        <span style='color:white'><?= $status ?><u></u><u></u></span>
                    </b>
                </p>
            </td>
            <td valign='bottom' style='border-top:none;border-left:none;border-bottom:solid #cccccc 1.0pt;border-right:solid #cccccc 1.0pt;background:white;padding:1.5pt 2.25pt 1.5pt 2.25pt;height:15.75pt'>
                <p class='MsoNormal' align='center' style='text-align:center'>
                    <b><span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black'><?= $empresa ?></span></b>
                    <b><span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif'><u></u><u></u></span></b></p>
            </td>
            <td valign='bottom' style='border-top:none;border-left:none;border-bottom:solid #cccccc 1.0pt;border-right:solid #cccccc 1.0pt;background:white;padding:1.5pt 2.25pt 1.5pt 2.25pt;height:15.75pt'>
                <p class='MsoNormal' align='center' style='text-align:center'>
                    <span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black'><?= $valor ?></span>
                    <span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif'><u></u><u></u></span>
                </p>
            </td>
            <td valign='bottom' style='border-top:none;border-left:none;border-bottom:solid #cccccc 1.0pt;border-right:solid #cccccc 1.0pt;background:white;padding:1.5pt 2.25pt 1.5pt 2.25pt;height:15.75pt'>
                <p class='MsoNormal'>
                    <span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black'><?= "{$origem}" ?>
                    </span>
                        <span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif'><u></u><u></u>
                    </span>
                </p>
            </td>
            <td valign='bottom' style='border-top:none;border-left:none;border-bottom:solid #cccccc 1.0pt;border-right:solid #cccccc 1.0pt;background:white;padding:1.5pt 2.25pt 1.5pt 2.25pt;height:15.75pt'>
                <p class='MsoNormal'>
                    <span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif;color:black'><?= "{$destino}" ?>
                    </span>
                        <span style='font-size:10.0pt;font-family:&quot;Arial&quot;,sans-serif'><u></u><u></u>
                    </span>
                </p>
            </td>
        </tr>
    </tbody>
</table>

