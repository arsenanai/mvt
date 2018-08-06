var app = new Vue({
	el:"#app",
	data:{
		message: `10110002/06.1000000003.TSE
EA2001
EB2002
SI 
SUPPLEMENTARY
INFORMATION
LINES
WITH
SPACES`,
		type: 'estimated time arrival',
		types:['departure','arrival','delay','estimated time arrival'],
		loading: false,
		submitted:false,
		dep:{//departure
		},
		arr:{//arrival
		},
		del:{//delay
		},
		est:{//estimated time arrival
		}
	},
	methods:{
		submit: function(){
			this.submitted=false;
			if(this.message.length>0){
				this.message = this.message.trim();
				var params = new URLSearchParams();
				params.append('type',this.type);
				params.append('message', this.message);
				axios.post('/index.php?r=site/messageSave', params)
				.then((response)=>{
					this.toggleLoading();
				  	this.submitted=true;
				  	if(typeof response.data ==='string'){
				  		alert(response.data);
				  	}else{
				  		if(this.type==='departure')
				  			this.dep = response.data;
				  		else if(this.type==='arrival')
				  			this.arr = response.data;
				  		else if(this.type==='delay')
				  			this.del = response.data;
				  		else if(this.type==='estimated time arrival')
				  			this.est = response.data;
				  		if(response.data.hasOwnProperty("error"))
				  			alert(response.data.error);
				  	}
				  	console.log(response.data);
				})
				.catch((error)=>{
				  	console.log(error);
				});
			}else{
				this.toggleLoading();
				alert('Fill MVT message first!');
			}
			this.toggleLoading();
		},
		toggleLoading(){
			setTimeout(()=>{
		        this.loading = !this.loading;
		    },1);
		}
	}
});