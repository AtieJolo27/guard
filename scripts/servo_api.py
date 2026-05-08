# scripts/servo_api.py
from flask import Flask, request, jsonify
from gpiozero import Servo
from time import sleep
import sys

app = Flask(__name__)

@app.route('/servo', methods=['POST'])
def move_servo():
    data = request.json
    pin = int(data['pin'])
    angle = int(data['angle'])

    servo = Servo(pin)
    value = (angle / 90) - 1
    servo.value = value
    sleep(0.5)
    servo.detach()

    return jsonify({'status': 'ok', 'pin': pin, 'angle': angle})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)