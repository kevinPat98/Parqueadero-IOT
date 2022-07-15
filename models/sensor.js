const mongoose = require('mongoose');

const sensorSchema = mongoose.Schema({
    numero:{
        type: Number,
        required: true
    },
    estado:{
        type: Boolean,
        required: true
    },
    fecha:{
        type: Date,
        default: Date.now()
    }

})

module.exports= mongoose.model('Sensor', sensorSchema)