<head>
	<meta http-equiv="Content-Language" content="en-us">
	<LINK href=../main.css rel=STYLESHEET type=text/css>
	<title>SMS-������</title>
	<meta http-equiv=content-type content='text/html; charset=windows-1251'>
</head>

<body>
	<center style="top:40%; position:absolute; width:100%">
		<div style="width:300px" class=but>SMS-������
			<div style="width:90%" class=but2>
				<?
				error_reporting(0);
				include_once '../inc/functions.php';

				// the function returns an MD5 of parameters passed
				// ������� ���������� MD5 ���������� �� ����������
				function ref_sign()
				{
					$params = func_get_args();
					$prehash = implode("::", $params);
					return md5($prehash);
				}

				// filtering junk off acquired parameters
				// ������ ���������� ��������� �� ������� ������
				foreach ($_REQUEST as $request_key => $request_value) {
					$_REQUEST[$request_key] = substr(strip_tags(trim($request_value)), 0, 250);
				}


				// service secret code
				// ��������� ��� �������
				$secret_code = "AICode";

				// collecting required data
				// �������� ����������� ������
				$purse        = $_REQUEST["s_purse"];        // sms:bank id        ������������� ���:�����
				$order_id     = $_REQUEST["s_order_id"];     // operation id       ������������� ��������
				$amount       = $_REQUEST["s_amount"];       // transaction sum    ����� ����������
				$clear_amount = $_REQUEST["s_clear_amount"]; // billing algorithm  �������� �������� ���������
				$inv          = $_REQUEST["s_inv"];          // operation number   ����� ��������
				$phone        = $_REQUEST["s_phone"];        // phone number       ����� ��������
				$sign         = $_REQUEST["s_sign_v2"];      // signature          �������

				// making the reference signature
				// ������� ��������� �������
				$reference = ref_sign($secret_code, $purse, $order_id, $amount, $clear_amount, $inv, $phone);

				if ($_GET["r"] == 'success') {
					echo "<b class=green>������ ������ ���������! ����� 1 ������ ���� ��������� ���.</b>";
				} else
	if ($_GET["r"] == 'fail') {
					echo "<b class=hp>������, ����� �������!</b>";
				} else
	if ($_GET["r"] == 'check') {
					// validating the signature
					// ���������, ����� �� �������
					if ($sign == $reference) {

						sql::q("UPDATE world SET smscount=smscount+1");
						$count = sql::q1("SELECT smscount FROM world")['smscount'];
						$pers = sql::q1("SELECT user,uid,level,chp,hp,cfight FROM users WHERE uid=" . intval($order_id));
						if (round($amount) == 1 and $pers) {
							if ($pers["cfight"] == 0) {
								$pers["chp"] += $pers["hp"] / 2;
								if ($pers["chp"] > $pers["hp"]) $pers["chp"] = $pers["hp"];
							}
							set_vars("dmoney=dmoney+" . floatval($amount) . ",phone_no='" . $phone . "',sms=sms+1,chp=" . $pers["chp"] . "", $order_id);
							if ($count % 10 > 6)
								say_to_chat("a", "�������� <b>" . (10 - $count % 10) . "</b> ��� �� ������� ���! <a href=\"http://localhost/AloneIsland/sms/i.php?r=info\" class=timef target=_blank>������.</a>", 0, '', '*');

							if ($count % 1000 == 0) {
								say_to_chat("a", "<b class=user>" . $pers["user"] . "</b><b class=level>[" . $pers["level"] . "]</b> ���������� <b>50 y.e.</b> �� ���� ������� ���!", 0, '', '*');
								set_vars("dmoney=dmoney+50", $order_id);
							} elseif ($count % 100 == 0) {
								say_to_chat("a", "<b class=user>" . $pers["user"] . "</b><b class=level>[" . $pers["level"] . "]</b> ���������� <b>10 y.e.</b> �� ���� ������� ���!", 0, '', '*');
								set_vars("dmoney=dmoney+10", $order_id);
							} elseif ($count % 50 == 0) {
								say_to_chat("a", "<b class=user>" . $pers["user"] . "</b><b class=level>[" . $pers["level"] . "]</b> ���������� <b>5 y.e.</b> �� ���� ������� ���!", 0, '', '*');
								set_vars("dmoney=dmoney+5", $order_id);
							} elseif ($count % 10 == 0) {
								say_to_chat("a", "<b class=user>" . $pers["user"] . "</b><b class=level>[" . $pers["level"] . "]</b> ���������� <b>1 y.e.</b> �� ���� ������� ���!", 0, '', '*');
								set_vars("dmoney=dmoney+1", $order_id);
							}

							say_to_chat("a", "��� �� ���� ��������� <b>" . floatval($amount) . " y.e.</b>.<a href=\"http://localhost/AloneIsland/sms/i.php?r=info\" class=timef>������.</a>", 1, $pers["user"], '*');
						}
					} else {
						echo "������!<script>location='http://AloneIsland/';</script>";
					}
				} else
	if ($_GET["r"] == 'info') {
					echo "�� ������ ���������� ����� ��� �������� ���������� 50% �������� ���������.<hr>";
					echo "�� ������ 10�� ���������� ��� ��� ������ ��� 1 �.�. �������������.<br>";
					echo "�� ������ 50�� - 5 �.�.<br>";
					echo "�� ������ 100�� - 10 �.�.<br>";
					echo "�� ������ 1000�� - 50 �.�.<br>";
				}
				?>
			</div>
		</div>
	</center>