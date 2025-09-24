// Configuración principal de Express y middlewares
const express = require('express');
const cors = require('cors');
const session = require('express-session');
const passport = require('passport');
const bodyParser = require('body-parser');
const db = require('./models');
require('./config/passport'); // Estrategia Google

const app = express();

app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(session({
  secret: 'supersecret',
  resave: false,
  saveUninitialized: true
}));
app.use(passport.initialize());
app.use(passport.session());

// Rutas principales
app.use('/api/usuarios', require('./routes/usuarios'));
app.use('/api/rifas', require('./routes/rifas'));
app.use('/api/reservas', require('./routes/reservas'));
app.use('/api/pagos', require('./routes/pagos'));
app.use('/api/auth', require('./routes/auth'));

// Sincronización de modelos y arranque
const PORT = process.env.PORT || 4000;
db.sequelize.sync().then(() => {
  app.listen(PORT, () => {
    console.log(`Servidor backend escuchando en puerto ${PORT}`);
  });
});
