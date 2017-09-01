<?php
	$difficulty=$emercoin->getdifficulty();
	$posDifficulty=$difficulty['proof-of-stake'];
	$powDifficulty=$difficulty['proof-of-work'];
	$powReward=round(bcdiv(5020,bcsqrt(bcsqrt($powDifficulty,8),8),8),2)."<br>";
?>

<div class="container">
	<h2>Proof-of-Stake</h2>
	  <div class="row">
	    <div class="col-sm-offset-2 col-sm-8">
	      Coins <input type="input" class="form-control" id="inputCoins" placeholder="Coins">
	    </div>
	  </div>
	  <div class="row">
	    <div class="col-sm-offset-2 col-sm-8">
	      Days <input type="input" class="form-control" id="inputAge" placeholder="Age (days) [31-90]" value="31">
	    </div>
	  </div>
	  <div class="row">
	    <div class="col-sm-offset-2 col-sm-8">
	      PoS Difficulty <input type="input" class="form-control" id="inputDiff" placeholder="Difficulty" value="<?php echo $posDifficulty; ?>">
	    </div>
	  </div>
	  <br>
	  <div class="row">
	    <div class="col-sm-offset-2 col-sm-2">
	    </div>
		<div class="col-sm-6">
	    	<table class="table">
					<tr><th><?php echo lang("MINTING_CHANCE");?><small><sub><?php echo lang("WITHIN_H");?></sub></small> [%]</th><th><?php echo lang("ESTIMATED_REWARD");?> [EMC]</th><tr>
					<tr><td id="mintChanceTD">-</td><td id="rewardTD">-</td></tr>
				</table>
	    </div>
	  </div>
	  <hr>
		<h2>Proof-of-Work - Merged Mining - EMC/BTC </h2>
		<div class="row">
	    <div class="col-sm-offset-2 col-sm-8">
	      Hashrate [TH/s] <input type="input" class="form-control" id="inputHashrate" placeholder="Hashrate [TH/s]">
	    </div>
	  </div>
	  <div class="row">
	    <div class="col-sm-offset-2 col-sm-8">
	      EMC PoW Difficulty <input type="input" class="form-control" id="inputPoWDiff" placeholder="EMC PoW-Difficulty" value="<?php echo $powDifficulty; ?>">
	    </div>
	  </div>
	  <div class="row">
	    <div class="col-sm-offset-2 col-sm-8">
	      BTC PoW Difficulty <input type="input" class="form-control" id="inputBTCPoWDiff" placeholder="BTC PoW-Difficulty" value="">
	    </div>
	  </div>
	  <br>
	  <div class="row">
	    <div class="col-sm-2">
	    </div>
		<div class="col-sm-8">
	    	<table class="table">
					<tr><th>Avg. time to find a EMC block</th><th>Est. reward per day [EMC]</th><th>PoW Block Reward [EMC]</th><tr>
					<tr><td id="powTimeToFindTD">-</td><td id="powPerDayTD">-</td><td id="powRewardTD"><?php echo $powReward; ?></td></tr>
				</table>
	    </div>
	  </div>
	  <div class="row">
	    <div class="col-sm-2">
	    </div>
		<div class="col-sm-8">
	    	<table class="table">
					<tr><th>Avg. time to find a BTC block</th><th>Est. reward per day [BTC]</th><th>PoW Block Reward [BTC]</th><tr>
					<tr><td id="powTimeToFindBTCTD">-</td><td id="powPerDayBTCTD">-</td><td id="powRewardBTCTD">12.5</td></tr>
				</table>
	    </div>
	  </div>

</div>

<script>
$('#inputCoins').on('keyup', function() {
	calculateProbBlockToday($('#inputAge').val(),$('#inputCoins').val(),$('#inputDiff').val());
});
$('#inputAge').on('keyup', function() {
	calculateProbBlockToday($('#inputAge').val(),$('#inputCoins').val(),$('#inputDiff').val());
});
$('#inputDiff').on('keyup', function() {
	calculateProbBlockToday($('#inputAge').val(),$('#inputCoins').val(),$('#inputDiff').val());
});

$('#inputHashrate').on('keyup', function() {
	calculatePowPerDay($('#inputHashrate').val(),$('#inputPoWDiff').val(),$('#inputBTCPoWDiff').val());
});$('#inputPoWDiff').on('keyup', function() {
	calculatePowPerDay($('#inputHashrate').val(),$('#inputPoWDiff').val(),$('#inputBTCPoWDiff').val());
});

/*function getBTCReward() {
	jQuery.ajaxSetup({async:false});
	$.get('https://blockchain.info/de/q/bcperblock',function(data){
		$('#powRewardBTCTD').html(data/100000000);
	});	
	jQuery.ajaxSetup({async:true});
};*/

function getBTCDiff() {
	jQuery.ajaxSetup({async:false});
	$.get('https://blockexplorer.com/api/status?q=getDifficulty',function(data){
		$('#inputBTCPoWDiff').val(data['difficulty']);
	});	
	jQuery.ajaxSetup({async:true});
};
window.onload = getBTCDiff;

function getProb(days, coins, difficulty) {
	var prob=0;
	if (days > 30) {
			var maxTarget = Math.pow(2, 224);
			var target = maxTarget/difficulty;
			var dayWeight = Math.min(days, 90)-30;
			prob = (target*coins*dayWeight)/Math.pow(2, 256);
	}
	return prob;
};
function calculateProbBlockToday(days, coins, difficulty) {
	var prob = getProb(days, coins, difficulty);
    var res = 1-(Math.pow((1-prob),600));
	res = res*144;
	res = res*100;

	var reward=0;
		if (days>30) {
			reward=((days*coins)/365)*0.06;
		}
	$('#mintChanceTD').html(Math.round(res * 1000000) / 1000000);
	$('#rewardTD').html(Math.round(reward * 1000000) / 1000000);
};
function calculatePowPerDay(hashrate, difficulty, btcdiff) {
	if (difficulty < 1) {
		difficulty=1;
		$('#inputPoWDiff').val('1');
	}
	hashrate=hashrate*Math.pow(10,12);
	var powReward=5020/Math.sqrt(Math.sqrt(difficulty));
	var powTime=(difficulty*4294967296)/hashrate;
	var BTCpowTime=(btcdiff*4294967296)/hashrate;
	var rewardPerDay=(86400/powTime)*powReward;
	var BTCrewardPerDay=(86400/BTCpowTime)*$('#powRewardBTCTD').html();
	var powUnit;
	var BTCpowUnit;
	if (hashrate != "" || hashrate != 0) {
		if (powTime<60) {
			powUnit="s";
		} else if (powTime >=60 && powTime <3600) {
			powUnit="m";
			powTime=powTime/60;
		} else if (powTime >=3600 && powTime <86400) {
			powUnit="h";
			powTime=powTime/3600;
		}  else if (powTime >=86400) {
			powUnit="d";
			powTime=powTime/86400;
		}
		if (BTCpowTime<60) {
			BTCpowUnit="s";
		} else if (BTCpowTime >=60 && BTCpowTime <3600) {
			BTCpowUnit="m";
			BTCpowTime=BTCpowTime/60;
		} else if (BTCpowTime >=3600 && BTCpowTime <86400) {
			BTCpowUnit="h";
			BTCpowTime=BTCpowTime/3600;
		}  else if (BTCpowTime >=86400) {
			BTCpowUnit="d";
			BTCpowTime=BTCpowTime/86400;
		}
		$('#powTimeToFindTD').html(Math.round(powTime * 1) / 1+' '+powUnit);
		$('#powPerDayTD').html(Math.round(rewardPerDay * 1000000) / 1000000);
		$('#powRewardTD').html(Math.round(powReward * 100) / 100);
		$('#powTimeToFindBTCTD').html(Math.round(BTCpowTime * 1) / 1+' '+BTCpowUnit);
		$('#powPerDayBTCTD').html(Math.round(BTCrewardPerDay * 1000000) / 1000000);
	} else {
		$('#powTimeToFindTD').html('-');
		$('#powPerDayTD').html('-');
		$('#powRewardTD').html(Math.round(powReward * 100) / 100);
		$('#powTimeToFindBTCTD').html('-');
		$('#powPerDayBTCTD').html('-');
	}
	
	
};
</script>
