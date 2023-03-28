const express = require('express');
const bodyParser = require('body-parser');
const MongoClient = require('mongodb').MongoClient 
const app = express();

var db;
    //username = dbuser
    //password = testing5627

MongoClient.connect('mongodb+srv://cluster0.ggflvpu.mongodb.net/cluster0" --apiVersion 1 --username dbuser', function (err, database) {
    if(err) return console.log(err);
    db = database;
    app.listen(3000, function () {
    console.log('listening to port 3000');    
    });

})

app.use(bodyParser.urlencoded({extended: true}));

app.listen(3000, function() {
    console.log('listening to port 3000');
} );

app.get('/', function(req, res) {
    //res.send('here i am GET');
    res.sendFile(__dirname + '/matt-for-400.html');

});

app.post('/todo', function(req, res) {
    
    db.collection('todo').save(req.body, function(err, result){
        if(err) return console.log(err);

        console.log('saved to our db');
        res.redirect('/');
    })
});