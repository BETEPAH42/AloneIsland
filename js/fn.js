$().ready(function() {
	$('.dv1').Drags({
		handler: '.handler',
		onMove: function(e) {
			$('.content').html('РўРµРєСѓС‰Р°СЏ РїРѕР·РёС†РёСЏ:(СЃР»РµРІР°:' + e.pageX + ' , СЃРІРµСЂС…Сѓ:' + e.pageY + ')');
		},
		onDrop: function(e){
			$('.content').html('РџСЂСЏРјРѕСѓРіРѕР»СЊРЅРёРє Р±СЂРѕС€РµРЅ! <br />РўРµРєСѓС‰Р°СЏ РїРѕР·РёС†РёСЏ:(СЃР»РµРІР°:<strong>' + e.pageX + '</strong> , СЃРІРµСЂС…Сѓ:<strong>' + e.pageY + '</strong>)');
		}
	});

	$('.dv2').Drags({
		handler: '.gb',                
		zIndex:200,
		opacity:.9
	});
});