const Sensor = require('../models/sensor')

var aux = require('../app')

exports.registro = async (data) =>{
   
    var ledArray = data.split(',')
    
   
    try{
        let sensor = {
            numero: ledArray[0],
            estado: ledArray[1] == 'O' ? true : false
        }; 

        product =  new Sensor(sensor)
        await product.save()
        
     

   }catch(error){
        console.log(error)
       
   }
}
   //module.exports = registro