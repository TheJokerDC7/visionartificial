<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="vistas/estilo.css">
 

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Demo</title>
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1 class="display-4">Demo V1</h1>
        </div>

        <div class="d-flex justify-content-center mb-4">
            <button type="button" class="btn btn-danger btn-lg" onclick="init()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera-fill" viewBox="0 0 16 16">
  <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
  <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"/>
</svg> Iniciar</button>
        </div>

        <div class="d-flex justify-content-center mb-4">
            <div id="webcam-container"></div>
        </div>

        <div class="mt-4">
            <h3>Predicciones:</h3>
            <div id="label-container"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>

    <script type="text/javascript">
        const URL = "https://teachablemachine.withgoogle.com/models/XxBTnjZLY/";

let model, webcam, labelContainer, maxPredictions;

async function init() {
    const modelURL = URL + "model.json";
    const metadataURL = URL + "metadata.json";

    model = await tmImage.load(modelURL, metadataURL);
    maxPredictions = model.getTotalClasses();

    const flip = true;
    webcam = new tmImage.Webcam(200, 200, flip);
    await webcam.setup();
    await webcam.play();
    window.requestAnimationFrame(loop);

    document.getElementById("webcam-container").appendChild(webcam.canvas);

    labelContainer = document.getElementById("label-container");
    for (let i = 0; i < maxPredictions; i++) {
        const predictionBarContainer = document.createElement("div");
        predictionBarContainer.className = "prediction-bar";
        
        const barElement = document.createElement("div");
        barElement.className = "bar";
        predictionBarContainer.appendChild(barElement);
        
        const labelElement = document.createElement("span");
        labelElement.className = "bar-label";
        predictionBarContainer.appendChild(labelElement);
        
        labelContainer.appendChild(predictionBarContainer);
    }
}

async function loop() {
    webcam.update();
    await predict();
    window.requestAnimationFrame(loop);
}

async function predict() {
    const prediction = await model.predict(webcam.canvas);
    for (let i = 0; i < maxPredictions; i++) {
        const barElement = labelContainer.childNodes[i].querySelector('.bar');
        const labelElement = labelContainer.childNodes[i].querySelector('.bar-label');
        
        barElement.style.width = `${prediction[i].probability * 100}%`;
        labelElement.textContent = `${prediction[i].className}: ${prediction[i].probability.toFixed(2)}`;
    }
}





    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQ
