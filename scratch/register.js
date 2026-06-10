const http = require('http');

const data = JSON.stringify({
  fullName: "Test API User",
  dob: "2000-01-01",
  mobile: "9998887776",
  email: "testapi@example.com",
  course: "course-1",
  category: "UG",
  mode: "Regular"
});

const options = {
  hostname: 'localhost',
  port: 5001,
  path: '/api/registration',
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Content-Length': data.length
  }
};

const req = http.request(options, res => {
  console.log(`STATUS: ${res.statusCode}`);
  res.on('data', d => {
    process.stdout.write(d);
  });
});

req.on('error', error => {
  console.error(error);
});

req.write(data);
req.end();
