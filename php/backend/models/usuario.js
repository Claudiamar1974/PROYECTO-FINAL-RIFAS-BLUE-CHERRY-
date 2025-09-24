const { DataTypes } = require('sequelize');
module.exports = (sequelize) => {
  const Usuario = sequelize.define('Usuario', {
    id: { type: DataTypes.BIGINT.UNSIGNED, autoIncrement: true, primaryKey: true },
    nombre: DataTypes.STRING,
    email: { type: DataTypes.STRING, unique: true },
    google_id: { type: DataTypes.STRING, unique: true },
    avatar: DataTypes.STRING,
    rol: { type: DataTypes.ENUM('admin','operador','usuario'), defaultValue: 'usuario' },
    creado_en: DataTypes.DATE,
    actualizado_en: DataTypes.DATE
  }, {
    tableName: 'usuarios',
    timestamps: false
  });
  return Usuario;
};
