import os
import subprocess
import sys

def install_and_import(package):
    try:
        import cv2
    except ImportError:
        subprocess.check_call([sys.executable, "-m", "pip", "install", "opencv-python"])
        import cv2
    return cv2

cv2 = install_and_import('opencv-python')

base_dir = r"c:\wamp64\www\GelSac\centro-ayuda-assets"

for root, dirs, files in os.walk(base_dir):
    for f in files:
        if f.endswith('.mp4'):
            mp4_path = os.path.join(root, f)
            jpg_path = os.path.splitext(mp4_path)[0] + '.jpg'
            
            if not os.path.exists(jpg_path):
                cap = cv2.VideoCapture(mp4_path)
                cap.set(cv2.CAP_PROP_POS_MSEC, 1000) # segundo 1
                ret, frame = cap.read()
                if ret:
                    cv2.imwrite(jpg_path, frame)
                    print(f"Generated: {jpg_path}")
                else:
                    print(f"Failed to read: {mp4_path}")
                cap.release()
            else:
                print(f"Exists: {jpg_path}")

print("Proceso finalizado.")
