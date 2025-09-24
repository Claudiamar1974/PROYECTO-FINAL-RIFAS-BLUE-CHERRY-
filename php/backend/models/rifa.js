const { DataTypes } = require('sequelize');
module.exports = (sequelize) => {
  const Rifa = sequelize.define('Rifa', {
    id: { type: DataTypes.BIGINT.UNSIGNED, autoIncrement: true, primaryKey: true },
    titulo: DataTypes.STRING,
    descripcion: DataTypes.TEXT,
    precio_por_numero: DataTypes.DECIMAL(10,2),
    total_numeros: DataTypes.INTEGER,
    fecha_inicio: DataTypes.DATE,
    fecha_fin: DataTypes.DATE,
    estado: { type: DataTypes.ENUM('activa','finalizada','cancelada'), defaultValue: 'activa' },
    id_ganador: { type: DataTypes.BIGINT.UNSIGNED, allowNull: true },
    creado_en: DataTypes.DATE,
    actualizado_en: DataTypes.DATE
  }, {
    tableName: 'rifas',
    timestamps: false
  });
  return Rifa;
};
