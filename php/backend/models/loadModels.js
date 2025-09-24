// Carga y asocia todos los modelos
const fs = require('fs');
const path = require('path');
const db = require('./index');

const basename = path.basename(__filename);
fs.readdirSync(__dirname)
  .filter(file => file !== basename && file.endsWith('.js'))
  .forEach(file => {
    const model = require(path.join(__dirname, file))(db.sequelize);
    db[model.name] = model;
  });

// Relaciones
const { Usuario, Rifa, Reserva, NumeroRifa, Pago } = db;
if (Usuario && Rifa && Reserva && NumeroRifa && Pago) {
  Usuario.hasMany(Reserva, { foreignKey: 'id_usuario' });
  Reserva.belongsTo(Usuario, { foreignKey: 'id_usuario' });

  Rifa.hasMany(Reserva, { foreignKey: 'id_rifa' });
  Reserva.belongsTo(Rifa, { foreignKey: 'id_rifa' });

  Rifa.hasMany(NumeroRifa, { foreignKey: 'id_rifa' });
  NumeroRifa.belongsTo(Rifa, { foreignKey: 'id_rifa' });

  Reserva.hasMany(NumeroRifa, { foreignKey: 'id_reserva' });
  NumeroRifa.belongsTo(Reserva, { foreignKey: 'id_reserva' });

  Reserva.hasMany(Pago, { foreignKey: 'id_reserva' });
  Pago.belongsTo(Reserva, { foreignKey: 'id_reserva' });

  Rifa.belongsTo(NumeroRifa, { as: 'Ganador', foreignKey: 'id_ganador' });
}

module.exports = db;
