const controller = require("./controller/controller");

var http = require("http");
var fs = require("fs");
var index = fs.readFileSync("index.html");
var SerialPort = require("serialport");

const dbconnection = require("./config/database");

dbconnection().then().catch();

const parsers = SerialPort.parsers;

const parser = new parsers.Readline({
  delimiter: "\r\n",
});

var port = new SerialPort("COM3", {
  baudRate: 9600,
  dataBits: 8,
  parity: "none",
  stopBits: 1,
  flowControl: false,
});

port.pipe(parser);

var app = http.createServer(function (req, res) {
  res.writeHead(200, { "Content-Type": "text/html" });
  res.end(index);
});

var io = require("socket.io").listen(app);

io.on("connection", function (socket) {
  console.log("Node is listening to port");
});

const aux = parser.on("data", function (data) {
  io.emit("data", data);
  controller.registro(data);
});

app.listen(3000);
