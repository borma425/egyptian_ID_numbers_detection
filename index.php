

  <style>
  body{
    background: #26192d;
    color: white;
    padding: 5rem;
  }
  table {
    border-collapse: collapse;
    width: 100%;
  }

  th,
  td {
    border: 2px solid yellow;
    padding: 8px;
    font-weight: bold;
    text-align: center;
  }
  .container {
  display: flex;
  justify-content: center;
  align-items: center;
}
textarea{
  max-width: 100%;
  width: 300px; /* Adjust the width as needed */
  padding: 10px;
  box-sizing: border-box;
  text-align: center;
}

</style>

<form class="container" method="POST" action="index.php">
    <textarea type="text" name="id_number" placeholder="رقم البطاقة">
</textarea>
<button type="submit">التحقق</button>

</form>



<?php


function arabicToEnglish($string) {
  $string = str_replace(' ', '', $string);
	$newNumbers = range(0, 9);
	// 1. Persian HTML decimal
	$persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
	// 2. Arabic HTML decimal
	$arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
	// 3. Arabic Numeric
	$arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
	// 4. Persian Numeric
	$persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

	$string =  str_replace($persianDecimal, $newNumbers, $string);
	$string =  str_replace($arabicDecimal, $newNumbers, $string);
	$string =  str_replace($arabic, $newNumbers, $string);
	$string  =  str_replace($persian, $newNumbers, $string);
  $id_number = intval($string);
return $id_number;
}



function Country_code_filter($number){

  switch ($number) {
    case '01':
        return 'القاهرة';
    case '02':
        return 'الإسكندرية';
    case '03':
        return 'بورسعيد';
    case '04':
        return 'السويس';
    case '11':
        return 'دمياط';
    case '12':
        return 'الدقهلية';
    case '13':
        return 'الشرقية';
    case '14':
        return 'القليوبية';
    case '15':
        return 'كفر الشيخ';
    case '16':
        return 'الغربية';
    case '17':
        return 'المنوفية';
    case '18':
        return 'البحيرة';
    case '19':
        return 'الإسماعيلية';
    case '21':
        return 'الجيزة';
    case '22':
        return 'بني سويف';
    case '23':
        return 'الفيوم';
    case '24':
        return 'المنيا';
    case '25':
        return 'أسيوط';
    case '26':
        return 'سوهاج';
    case '27':
        return 'قنا';
    case '28':
        return 'أسوان';
    case '29':
        return 'الأقصر';
    case '31':
        return 'البحر الأحمر';
    case '32':
        return 'الوادي الجديد';
    case '33':
        return 'مطروح';
    case '34':
        return 'شمال سيناء';
    case '35':
        return 'جنوب سيناء';
    case '88':
        return 'خارج جمهورية مصر العربية';
    default:
        return 'لم يتم التعرف علي المحافظة';
}

}




function Detect_Egyption_ID($id_num){


$id_num = $id_num;

#14 (digit) of numbers
preg_match('/(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)(\d)/', $id_num, $matches);
if (isset($matches[13]) && isset($matches[14])) {
    $name   = "غير معروف"; # Temp Name
    $gender = $matches[13];
    $gender   = ( $matches[13] % 2 == 0 ) ? "انثي" : "ذكر";

    $born   = $matches[1];
    $born   = ( $born == 2 ) ? 1900 : 2000;
    $day    = $matches[6].$matches[7];
    $month  = $matches[4].$matches[5];
    $year   = $matches[2].$matches[3];
    $year   = ( $matches[2] == 0 ) ? 200 . $matches[3] :  19 . $year;
    $Country_code  = $matches[8].$matches[9];
    $Country_name  = Country_code_filter($Country_code);


$birthday = $year.$month.$day;
$currentDate = new DateTime();
$birthdate = new DateTime($birthday);
$age = $currentDate->diff($birthdate)->y;



    return "

    <tr>
      <td>$name</td>
      <td>$gender</td>
      <td>$born</td>
      <td>$Country_name</td>
      <td>$day</td>
      <td>$month</td>
      <td>$year</td>
      <td>$age</td>

    </tr>
<!-- ------Next ID --------- -->




    ";

} else {
    return "حدث خطأ في استخراج بيانات هذة الهوية";
}

}






function EG_Command(array $ids ){

  echo "




<table dir='rtl'>
  <tr>
    <th>الإسم</th>
    <th>النوع</th>
    <th>مواليد</th>
    <th>المدينة</th>
    <th>يوم</th>
    <th>شهر</th>
    <th>سنة</th>
    <th>العمر</th>

  </tr>




  ";
  foreach ($ids as $id) {
    $id_number = arabicToEnglish($id);
    echo Detect_Egyption_ID($id_number);
}

echo "
</table>";

}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['id_number'])) {

    

$id_number = $_POST['id_number'];

$id_multi_numbers = explode("\n", $id_number);

# you can add multi id's in this array
EG_Command(

  $id_multi_numbers

);


}
}
