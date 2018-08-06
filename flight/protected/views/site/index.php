<?php
/* @var $this SiteController */
$this->pageTitle="MVT message online parsing";
?>
<div id=app v-cloak>
	<!--<div class="col-md-3">
		<label>Recent messages</label>
		<ul class="list-group">
			<li class="list-group-item">
				<a href="">2018-01-01 12:12</a>
			</li>
		</ul>
	</div>-->
	<!--<div class="col-md-12">-->
		<div>
			<form class="form-inline">
				<div class=form-group>
					<label>Type of MVT</label>
					<select v-model=type class="form-control" @change="submitted=false">
						<option v-for="type in types">{{type}}</option>
					</select>
				</div>
			</form>
			<form>
			  <div class="form-group">
			  	<label>MVT message body</label>
			    <textarea class="form-control" v-model="message" name="message" rows="10" required="true" 
			    placeholder="Enter MVT message here">
			    </textarea>
			  </div>
		  </form>
		  <a @click="submit" class="btn btn-default">Submit</a>
		</div>
		<hr>
		<img src="images/hourglass.svg" v-if="loading==true">
		<div v-if=" type=='departure' && submitted==true ">
			<form class="form-inline">
			  <div class="form-group">
			    <label>Airline Code</label>
			    <input type="text" size=3 v-model="dep.airlineCode">
			  </div>
			  <div class="form-group">
			    <label>Flight Number</label>
			    <input type="text" size=5 v-model="dep.flightNumber">
			  </div>
			  <div class="form-group">
			    <label>Date</label>
			    <input type="text" size=3 v-model="dep.date">
			  </div>
			  <div class="form-group">
			    <label>Aircraft Reg.</label>
			    <input type="text" size=10 v-model="dep.aircraftReg">
			  </div>
			  <div class="form-group">
			    <label>Departure Airport Code</label>
			    <input type="text" size=3 v-model="dep.departureAirportCode">
			  </div>
			</form>
			<form class="form-inline">
			  <div class="form-group">
			    <label>Off-block time (HHMM)</label>
			    <input type="text" size=4 v-model="dep.offblockTime">
			  </div>
			  <div class="form-group">
			    <label>Airborne time (HHMM)</label>
			    <input type="text" size=4 v-model="dep.airborneTime">
			  </div>
			  <div class="form-group">
			    <label>Estimated Arrival (HHMM)</label>
			    <input type="text" size=4 v-model="dep.estimatedArrival">
			  </div>
			  <div class="form-group">
			    <label>Destination Airport Code</label>
			    <input type="text" size=3 v-model="dep.destinationAirportCode">
			  </div>
			</form>
			<form class="form-inline">
			  <div class="form-group">
			    <label>Passenger information</label>
			    <input type="text" v-model="dep.passengerInformation">
			  </div>
			</form>
			<form class="form-inline">
			  <div class="form-group">
			    <label>Delay Code 1</label>
			    <input type="text" size=2 v-model="dep.delayCode1">
			  </div>
			  <div class="form-group">
			    <label>Delay time 1 (HHMM)</label>
			    <input type="text" size=4 v-model="dep.delayTime1">
			  </div>
			</form>
			<form class="form-inline">
				<div class="form-group">
			    <label>Delay Code 2</label>
			    <input type="text" size=2 v-model="dep.delayCode2">
			  </div>
			  <div class="form-group">
			    <label>Delay time 2 (HHMM)</label>
			    <input type="text" size=4 v-model="dep.delayTime2">
			  </div>
			</form>
			<form>
			  <div class="form-group">
			    <label>Supplementary Information</label>
			    <textarea class="form-control" v-model="dep.supplementaryInformation" rows=5></textarea>
			  </div>
			</form>
		</div>
		<div v-if="type=='arrival' && submitted==true"><!--arrival-->
			<form class="form-inline">
			  <div class="form-group">
			    <label>Airline Code</label>
			    <input type="text" size=3 v-model="arr.airlineCode">
			  </div>
			  <div class="form-group">
			    <label>Flight Number</label>
			    <input type="text" size=5 v-model="arr.flightNumber">
			  </div>
			  <div class="form-group">
			    <label>Date</label>
			    <input type="text" size=3 v-model="arr.date">
			  </div>
			  <div class="form-group">
			    <label>Aircraft Reg.</label>
			    <input type="text" size=10 v-model="arr.aircraftReg">
			  </div>
			  <div class="form-group">
			    <label>Arrival Airport Code</label>
			    <input type="text" size=3 v-model="arr.arrivalAirportCode">
			  </div>
			</form>
			<form class=form-inline>
				<div class="form-group">
			    <label>Touchdown time (HHMM)</label>
			    <input type="text" size=4 v-model="arr.touchdownTime">
			  </div>
			  <div class="form-group">
			    <label>On-block time (HHMM)</label>
			    <input type="text" size=4 v-model="arr.onblockTime">
			  </div>
			</form>
			<form>
			  <div class="form-group">
			    <label>Supplementary Information</label>
			    <textarea class="form-control" rows=5 v-model="arr.supplementaryInformation"></textarea>
			  </div>
			</form>
		</div>
		<div v-if="type=='delay' && submitted==true"><!--delay-->
			<form class="form-inline">
				<div class="form-group">
			    <label>Airline Code</label>
			    <input type="text" size=3 v-model="del.airlineCode">
			  </div>
			  <div class="form-group">
			    <label>Flight Number</label>
			    <input type="text" size=5 v-model="del.flightNumber">
			  </div>
			  <div class="form-group">
			    <label>Date</label>
			    <input type="text" size=3 v-model="del.date">
			  </div>
			  <div class="form-group">
			    <label>Aircraft Reg.</label>
			    <input type="text" size=10 v-model="del.aircraftReg">
			  </div>
			  <div class="form-group">
			    <label>Departure Airport Code</label>
			    <input type="text" size=3 v-model="del.departureAirportCode">
			  </div>
			</form>
			<form class="form-inline">
			  <div class="form-group">
			    <label>Estimated Departure Time (DDHHMM)</label>
			    <input type="text" size=6 v-model="del.estimatedDepartureTime">
			  </div>
			  <div class="form-group">
			    <label>Delay Reason Code 1</label>
			    <input type="text" size=2 v-model="del.delayReasonCode1">
			  </div>
			  <div class="form-group">
			    <label>Delay Reason Code 2</label>
			    <input type="text" size=2 v-model="del.delayReasonCode2">
			  </div>
			</form>
			<form>
			  <div class="form-group">
			    <label>Supplementary Information</label>
			    <textarea class="form-control" rows=5 v-model="del.supplementaryInformation"></textarea>
			  </div>
			</form>
		</div>
		<div v-if="type=='estimated time arrival' && submitted==true">
			<form class="form-inline">
				<div class="form-group">
					<label>Airline Code</label>
					<input type="text" size=3 v-model="est.airlineCode">
				</div>
				<div class="form-group">
					<label>Flight Number</label>
					<input type="text" size=5 v-model="est.flightNumber">
				</div>
				<div class="form-group">
					<label>Date</label>
					<input type="text" size=3 v-model="est.date">
				</div>
				<div class="form-group">
					<label>Aircraft Reg.</label>
					<input type="text" size=10 v-model="est.aircraftReg">
				</div>
				<div class="form-group">
					<label>Departure Airport Code</label>
					<input type="text" size=3 v-model="est.departureAirportCode">
				</div>
			</form>
			<form class=form-inline>
				<div class="form-group">
					<label>Estimated Arrival (HHMM)</label>
					<input type="text" size=4 v-model="est.estimatedArrival">
				</div>
				<div class="form-group">
					<label>Estimated On-block Time (HHMM)</label>
					<input type="text" size=4 v-model="est.estimatedOnblockTime">
				</div>
			</form>
			<form>
			  <div class="form-group">
			    <label>Supplementary Information</label>
			    <textarea class="form-control" rows=5 v-model="est.supplementaryInformation"></textarea>
			  </div>
			</form>
		</div>
	<!--</div>-->
</div>
<script src="js/vue.min.js"></script>
<script src="js/axios.min.js"></script>
<script src="js/main111.js"></script>