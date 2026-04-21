import os
from huggingface_hub import upload_folder, login

login(os.getenv("HF_TOKEN"))

upload_folder(
    repo_id="ZEROTSUDIOS/chatbot-bipa-api",
    folder_path="py",
    repo_type="space"  
)
