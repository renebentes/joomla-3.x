<?php
//Modificar apenas as variáveis $chave e $ambiente
//$chave - Token gerado no SISGRU
//$ambiente - H para Homologação e P para Produção
$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiIxNjAwODYifQ.fY4bWesL85B_vFSOmRUyfrawte-SjSuqKcFQTfyfMQVFKyl6gfJKX63o_wElLkb3MHXl5xmQG9zlQasv5V561uq-R8uV6Gi35iXy36hk6wdc8LyLk-WgVD5TN4fyCCrZ5VH6tuayM7xmZ3fPyPdfJFknCCao48E2skbptEHS-8VUjFKAUObd_oFblDsyc8jC0cYPfX7p8IbO1kdeibqBbu-wpnGczsmoWftMkmS82Y-U9EqcRcY5IN10IcVFg_IJ7Mo5SeH3snfrcOMVP-DMjUH0MefmHUqN0eMGlBbeZK1rHxvRXfB7Ual9PORzyhuTO5kzIYK90EW1sT2qNl4TXA";
$ambiente="H";
//
if ($ambiente=='H')
{
 $url = 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
elseif ($ambiente=='P')
{
 $url = 'https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}
$codigoServico=$_POST["codigoServico"];
$referencia=$_POST["referencia"];
$nomeContribuinte=$_POST["nomeContribuinte"];
$competencia=str_replace("/","",$_POST["competencia"]);
if ($_POST["vencimento"]=="")
{
 $vencimento="";
}
else
{
 $vencimento=date("dmY",strtotime($_POST["vencimento"]));
 if (strtotime($_POST["vencimento"])<strtotime(date('Y-m-d')))
 {
  echo '<script type="text/javascript">alert("Data de vencimento menor que data atual.");</script>';
  echo '<script type="text/javascript">history.back();</script>';
  exit;
 }
}
$cnpjCpf=str_replace("/","",$_POST["cnpjCpf"]);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$_POST["valorPrincipal"]);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
$valorDescontos=str_replace(".","",$_POST["valorDescontos"]);
$valorDescontos=str_replace(",",".",$valorDescontos);
$valorOutrasDeducoes=str_replace(".","",$_POST["valorOutrasDeducoes"]);
$valorOutrasDeducoes=str_replace(",",".",$valorOutrasDeducoes);
$valorMulta=str_replace(".","",$_POST["valorMulta"]);
$valorMulta=str_replace(",",".",$valorMulta);
$valorJuros=str_replace(".","",$_POST["valorJuros"]);
$valorJuros=str_replace(",",".",$valorJuros);
$valorOutrosAcrescimos=str_replace(".","",$_POST["valorOutrosAcrescimos"]);
$valorOutrosAcrescimos=str_replace(",",".",$valorOutrosAcrescimos);
$data = array(
  "codigoServico" => $codigoServico,
  "referencia" => $referencia,
  "competencia" => $competencia,
  "vencimento" => $vencimento,
  "cnpjCpf" => $cnpjCpf,
  "nomeContribuinte" =>  $nomeContribuinte,
  "valorPrincipal" => $valorPrincipal,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "modoNavegacao" => "2"
);
$data_string = json_encode($data);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
if ($result === false)
{
 echo '<p style="text-align:center;">Erro ao acessar o site do PagTesouro</p>';
}
else
{
 $result=json_decode($result);
 if (is_array($result))
 {
  $i=1;
  while ($i<=count($result))
  {
   echo '<p style="text-align:center;">ERRO: '.$result[$i-1]->{'codigo'}."-".$result[$i-1]->{'descricao'}.'<br>';
   $i++;
  }
  echo '<a href="javascript:history.back()">Voltar</a><p>';
 }
 else
 {
  echo '<script type="text/javascript">window.open(\''.$result->{'proximaUrl'}.'\', \'_blank\');</script>';
  echo '<script type="text/javascript">history.back();</script>';
 }
}
