const mongoose = require('mongoose');

require('dotenv').config({path: '.ENV'})

const dbconnection = async () =>{
    try {
        await mongoose.connect(
            process.env.DB_MONGO,
            {
                useNewUrlParser: true,
                useUnifiedTopology: true,
            }
        )
        console.log('db conectada')
    } catch (error) {
        console.log(error)
        throw new Error('Error a la hora de iniciar BD');
    }
}

module.exports= dbconnection;