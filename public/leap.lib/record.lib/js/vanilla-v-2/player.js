(function(){            
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
}).call(this);