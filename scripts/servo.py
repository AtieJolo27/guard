from gpiozero import Servo
from time import sleep
import sys

pin = int(sys.argv[1])
angle = int(sys.argv[2])

servo = Servo(pin)

# Convert angle 0-180 to gpiozero value -1 to 1
value = (angle / 90) - 1

servo.value = value
sleep(0.5)

servo.detach()