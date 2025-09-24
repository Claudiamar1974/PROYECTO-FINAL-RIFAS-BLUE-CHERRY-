const GoogleStrategy = require('passport-google-oauth20').Strategy;
const passport = require('passport');
const db = require('../models');

passport.serializeUser((user, done) => {
  done(null, user.id);
});

passport.deserializeUser(async (id, done) => {
  try {
    const user = await db.Usuario.findByPk(id);
    done(null, user);
  } catch (err) {
    done(err, null);
  }
});

passport.use(new GoogleStrategy({
  clientID: process.env.GOOGLE_CLIENT_ID || 'GOOGLE_CLIENT_ID',
  clientSecret: process.env.GOOGLE_CLIENT_SECRET || 'GOOGLE_CLIENT_SECRET',
  callbackURL: '/api/auth/google/callback',
}, async (accessToken, refreshToken, profile, done) => {
  try {
    let user = await db.Usuario.findOne({ where: { google_id: profile.id } });
    if (!user) {
      user = await db.Usuario.create({
        nombre: profile.displayName,
        email: profile.emails[0].value,
        google_id: profile.id,
        avatar: profile.photos[0].value,
      });
    }
    return done(null, user);
  } catch (err) {
    return done(err, null);
  }
}));
