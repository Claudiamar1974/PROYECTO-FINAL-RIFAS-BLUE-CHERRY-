const { DataTypes } = require('sequelize');
module.exports = (sequelize) => {
  const NumeroRifa = sequelize.define('NumeroRifa', {
    id: { type: DataTypes.BIGINT.UNSIGNED, autoIncrement: true, primaryKey: true },
    id_rifa: { type: DataTypes.BIGINT.UNSIGNED, allowNull: false },
    id_reserva: { type: DataTypes.BIGINT.UNSIGNED, allowNull: true },
    numero: { type: DataTypes.INTEGER, allowNull: false },
    estado: { type: DataTypes.ENUM('libre','reservado','vendido'), defaultValue: 'libre' },
    creado_en: DataTypes.DATE,
    actualizado_en: DataTypes.DATE
  }, {
    tableName: 'numeros_rifa',
    timestamps: false
  });
  return NumeroRifa;
};
