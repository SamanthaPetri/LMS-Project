const express = require('express');
const app = express();
const PORT = process.env.PORT || 5000;

const base = `${__dirname}/public`;

app.use(express.static("public"));

app.get("/", (req, res) => {
    res.sendFile(`${base}/index.html`);
});

app.listen(PORT, () => {
    console.log(`listening on port ${PORT}`);
});