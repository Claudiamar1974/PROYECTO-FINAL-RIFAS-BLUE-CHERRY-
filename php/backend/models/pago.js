const { DataTypes } = require('sequelize');
module.exports = (sequelize) => {
  const Pago = sequelize.define('Pago', {
    id: { type: DataTypes.BIGINT.UNSIGNED, autoIncrement: true, primaryKey: true },
    id_reserva: { type: DataTypes.BIGINT.UNSIGNED, allowNull: false },
    id_pago_mp: DataTypes.STRING,
    estado: { type: DataTypes.ENUM('aprobado','rechazado','pendiente'), defaultValue: 'pendiente' },
    monto: DataTypes.DECIMAL(10,2),
    metodo_pago: DataTypes.STRING,
    pagado_en: DataTypes.DATE,
    creado_en: DataTypes.DATE,
    actualizado_en: DataTypes.DATE
  }, {
    tableName: 'pagos',
    timestamps: false
  });
  return Pago;
};
