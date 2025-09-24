const { Sequelize } = require('sequelize');

const sequelize = new Sequelize(process.env.DB_NAME || 'rifasdb', process.env.DB_USER || 'root', process.env.DB_PASS || '', {
  host: process.env.DB_HOST || 'localhost',
  dialect: 'mysql',
  logging: false,
});

const db = {};
db.Sequelize = Sequelize;
db.sequelize = sequelize;

// Modelos
// Se cargarán dinámicamente en index.js

module.exports = db;
