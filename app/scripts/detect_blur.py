# storage/app/scripts/detect_blur.py

import cv2
import argparse
import json
import sys

def calculate_blurriness(image_path):
    """Calculates the variance of the Laplacian to detect blur."""
    try:
        # Read the image from the path
        image = cv2.imread(image_path)
        
        # If the image cannot be read, return an error
        if image is None:
            return None, "Image not found or could not be opened."

        # Convert the image to grayscale
        gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

        # Compute the Laplacian of the image and then compute the variance
        # CV_64F is a 64-bit float, which is important to avoid overflow
        variance = cv2.Laplacian(gray, cv2.CV_64F).var()
        
        return variance, None
    except Exception as e:
        return None, str(e)

if __name__ == "__main__":
    # Set up argument parser
    ap = argparse.ArgumentParser()
    ap.add_argument("-i", "--image", required=True, help="path to input image")
    ap.add_argument("-t", "--threshold", type=float, default=100.0, help="blur threshold")
    args = vars(ap.parse_args())

    # Calculate blurriness
    focus_measure, error = calculate_blurriness(args["image"])
    
    # Prepare the result dictionary
    result = {
        "success": False,
        "score": 0,
        "is_blurry": None,
        "threshold": args["threshold"],
        "error": None
    }

    if error:
        result["error"] = error
        print(json.dumps(result))
        sys.exit(1) # Exit with a non-zero status code to indicate failure

    # Determine if the image is blurry
    is_blurry = focus_measure < args["threshold"]

    # Update result dictionary with the outcome
    result["success"] = True
    result["score"] = focus_measure
    result["is_blurry"] = bool(is_blurry)
    # Print the result as a JSON string
    print(json.dumps(result, indent=4))