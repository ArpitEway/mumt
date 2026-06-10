import mysql from 'mysql2/promise';

async function run() {
  try {
    const connection = await mysql.createConnection({
      host: 'localhost',
      user: 'root',
      password: ''
    });
    const [rows] = await connection.execute('SHOW DATABASES');
    console.log("Databases:", rows);
    await connection.end();
  } catch (error) {
    console.error(error);
  }
}
run();
