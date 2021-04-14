(function(){
	
	
	(player1.controller) = new Leap.Controller({background: false});
	(player1.controller).use('playback', {
	                        autoplay:true,
	                        loop: false,
	                        pauseHotkey: true,
	                        pauseOnHand: false,
	                  })
	                  .use('riggedHand')
	                  .connect();
	                  
	(player2.controller) = new Leap.Controller({background: false});
	(player2.controller).use('playback', {
	                        pauseHotkey: false,
	                        pauseOnHand: false,
	                  })
	                  .use('riggedHand',{
	                    materialOptions: {
	                      color: new THREE.Color(0xff0000)
	                    }
	                  })
	                  .connect();
	//(player1.controller).on('frame', onProtocol1);
	//(player2.controller).on('frame', onProtocol2);
}).call(this);