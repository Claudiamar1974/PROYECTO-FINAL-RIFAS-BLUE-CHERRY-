const { DataTypes } = require('sequelize');
module.exports = (sequelize) => {
  const Reserva = sequelize.define('Reserva', {
    id: { type: DataTypes.BIGINT.UNSIGNED, autoIncrement: true, primaryKey: true },
    id_usuario: { type: DataTypes.BIGINT.UNSIGNED, allowNull: false },
    id_rifa: { type: DataTypes.BIGINT.UNSIGNED, allowNull: false },
    estado: { type: DataTypes.ENUM('reservado','pagado','cancelado','expirado'), defaultValue: 'reservado' },
    reservado_en: DataTypes.DATE,
    expira_en: DataTypes.DATE,
    creado_en: DataTypes.DATE,
    actualizado_en: DataTypes.DATE
  }, {
    tableName: 'reservas',
    timestamps: false
  });
  return Reserva;
};
