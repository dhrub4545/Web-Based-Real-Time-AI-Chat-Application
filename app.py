# import os
# import google.generativeai as genai

# genai.configure(api_key="YOUR_API_KEY")

# # Create the model
# generation_config = {
#   "temperature": 1,
#   "top_p": 0.95,
#   "top_k": 40,
#   "max_output_tokens": 8192,
#   "response_mime_type": "text/plain",
# }

# model = genai.GenerativeModel(
#   model_name="gemini-1.5-pro-002",
#   generation_config=generation_config,
# )

# chat_session = model.start_chat(
#   history=[
#     {
#       "role": "user",
#       "parts": [
#         "i am a  student.\n",
#       ],
#     }
#   ]
# )

# response = chat_session.send_message("write python to use gmemini model")

# print(response.text)
from flask import Flask, request, jsonify
from flask_cors import CORS
import google.generativeai as genai

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

# Configure API key
genai.configure(api_key="AIzaSyDphRJTctpvY7UdW6MxtBpo8gkTcwS_K1E")

generation_config = {
    "temperature": 1,
    "top_p": 0.95,
    "top_k": 40,
    "max_output_tokens": 8192,
    "response_mime_type": "text/plain",
}

model = genai.GenerativeModel(
    model_name="gemini-1.5-pro-002",
    generation_config=generation_config,
)

@app.route('/chat', methods=['POST'])
def chat():
    user_message = request.json.get('message')
    if not user_message:
        return jsonify({"error": "Message is required"}), 400

    chat_session = model.start_chat(
        history=[
            {"role": "user", "parts": [user_message]},
           
        ]
    )
    response = chat_session.send_message(user_message)
    return jsonify({"response": response.text})

if __name__ == '__main__':
    app.run(debug=True)

